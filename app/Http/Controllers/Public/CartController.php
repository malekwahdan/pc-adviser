<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);


        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'The requested quantity is not available.');
        }


        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->sale_price
                ? $item->product->sale_price * $item->quantity
                : $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.10;
        $shipping = $cartItems->count() > 0 ? 5.00 : 0.00;
        $total = $subtotal + $tax + $shipping;

        $recommendedProducts = Product::where('status', 'in_stock')
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'recommendedProducts'));
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);


        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }


        if ($request->quantity > $cart->product->stock_quantity) {
            return back()->with('error', 'The requested quantity is not available.');
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Cart $cart)
    {
        
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
    public function buyNow(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);

    if ($request->quantity > $product->stock_quantity) {
        return back()->with('error', 'The requested quantity is not available.');
    }

    Cart::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ],
        [
            'quantity' => $request->quantity,
        ]
    );

    return redirect()->route('checkout.index');
}

}
