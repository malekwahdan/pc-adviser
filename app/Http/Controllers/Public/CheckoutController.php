<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    public function index()
    {
        // Get cart items for the current user
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $formattedCartItems = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $price = $product->sale_price ?? $product->price;

            $formattedCartItems[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $item->quantity,
                'image' => $product->thumbnail,
                'total' => $price * $item->quantity
            ];

            $subtotal += $price * $item->quantity;
        }

        $shippingMethods = ShippingMethod::all();
        $user = Auth::user();

        $taxRate = 0.10;
        $tax = $subtotal * $taxRate;

        $shippingCost = $shippingMethods->first()->price ?? 0;

        $total = $subtotal + $tax + $shippingCost;

        return view('checkout.index', compact(
            'formattedCartItems',
            'subtotal',
            'tax',
            'shippingCost',
            'total',
            'shippingMethods',
            'user'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'shipping_method' => 'required|exists:shipping_methods,id',
            'payment_method' => 'required|in:card,paypal,google_pay,apple_pay',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method);

        $subtotal = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $item->product;
            $price = $product->sale_price ?? $product->price;

            $orderItems[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $item->quantity
            ];

            $subtotal += $price * $item->quantity;
        }

        // Apply tax
        $taxRate = 0.10;
        $tax = $subtotal * $taxRate;

        // Calculate final total
        $total = $subtotal + $tax + $shippingMethod->price;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingMethod->price,
                'total' => $total,
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'shipping_method_id' => $shippingMethod->id,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->street_address,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            // Create a pending payment record
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'PENDING',
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => 'pending'
            ]);

            DB::commit();


            if ($request->payment_method === 'card') {
                return $this->processStripePayment($order, $orderItems, $shippingMethod, $tax);
            } else {

                return redirect()->route('checkout.success', ['order' => $order->id]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processStripePayment($order, $orderItems, $shippingMethod, $tax)
    {
        $lineItems = [];


        foreach ($orderItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => round($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // Add shipping as a line item
        if ($shippingMethod->price > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Shipping: ' . $shippingMethod->name,
                    ],
                    'unit_amount' => round($shippingMethod->price * 100),
                ],
                'quantity' => 1,
            ];
        }

        // Add tax as a line item
        if ($tax > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Tax',
                    ],
                    'unit_amount' => round($tax * 100),
                ],
                'quantity' => 1,
            ];
        }

        try {

            Stripe::setApiKey(config('services.stripe.secret'));

            // Create a Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.stripe.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.stripe.cancel', ['order' => $order->id]),
                'customer_email' => Auth::user()->email,
            ]);

            // Store Stripe session ID with the order
            $order->update(['stripe_session_id' => $session->id]);

            // Redirect to Stripe Checkout
            return redirect($session->url);

        } catch (ApiErrorException $e) {

            DB::rollBack();
            return back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request, $orderId)
    {
        $sessionId = $request->session_id;
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);

            // Check payment status
            if ($session->payment_status === 'paid') {
                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);

                // Update payment record
                Payment::where('order_id', $order->id)->update([
                    'status' => 'successful',
                    'transaction_id' => $session->payment_intent
                ]);

                // Update product stock quantities
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    Product::find($item->product_id)
                        ->decrement('stock_quantity', $item->quantity);
                }

                // Clear the user's cart
                Cart::where('user_id', Auth::id())->delete();

                // Send order confirmation email
                $this->sendOrderConfirmationEmail($order->id);

                return redirect()->route('checkout.success', ['order' => $order->id]);
            } else {
                return redirect()->route('checkout.index')
                    ->with('error', 'Payment was not completed. Please try again.');
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    public function sendOrderConfirmationEmail($orderId)
    {
        try {
            $order = Order::with(['orderItems.product', 'user', 'shippingMethod'])->find($orderId);
            Mail::to(Auth::user()->email)->send(new OrderConfirmation($order));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function stripeCancel($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'failed'
        ]);

        Payment::where('order_id', $order->id)->update([
            'status' => 'failed'
        ]);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Please try again.');
    }

    public function success($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
