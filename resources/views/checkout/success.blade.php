
@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-lg fs-2"></i>
                    </div>
                    <h2 class="fw-bold">Thank You for Your Order!</h2>
                    <p class="text-muted">Your order has been placed successfully. A confirmation email has been sent to {{ $order->user->email }}</p>
                </div>
            </div>

            <!-- Order Info Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">Order #{{ $order->order_number }}</h5>
                            <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="btn btn-outline-dark btn-sm">View All Orders</a>
                    </div>

                    <!-- Order Items -->
                    @foreach($order->orderItems as $item)
                    <div class="row mb-3 py-3 border-bottom">
                        <div class="col-md-2 col-3 mb-2 mb-md-0">
                            <div class="bg-light rounded" style="height: 70px; width: 70px;">
                                @if($item->product->thumbnail)
                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}" class="img-fluid rounded">
                                @else
                                <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-image"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7 col-9 mb-2 mb-md-0">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-3 col-12 text-md-end">
                            <span>${{ number_format($item->price, 2) }}</span>
                        </div>
                    </div>
                    @endforeach

                    <!-- Shipping & Payment Info -->
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <h6 class="mb-3">Shipping Address</h6>
                            <address class="mb-0">
                                {{ $order->shipping_address }}

                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Payment Method</h6>
                            <p class="d-flex align-items-center">
                                @if($order->payment_method == 'card')
                                <i class="bi bi-credit-card me-2"></i>
                                Card ending in {{ substr($order->payment->transaction_id ?? '0000', -4) }}
                                @elseif($order->payment_method == 'paypal')
                                <i class="bi bi-paypal me-2"></i>
                                PayPal
                                @else
                                <i class="bi bi-wallet me-2"></i>
                                {{ ucfirst($order->payment_method) }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <span>-${{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold mt-3 pt-3 border-top">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Information Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Delivery Information</h6>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-truck text-primary me-3 fs-4"></i>
                        <div>
                            <p class="mb-1 fw-medium">Estimated Delivery: {{ $order->shippingMethod->estimated_delivery_time ?? 'Processing' }}</p>
                            <p class="text-muted small mb-0">{{ $order->shippingMethod->name ?? 'Standard Shipping' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('p.index') }}" class="btn btn-outline-dark">Continue Shopping</a>

            </div>
        </div>
    </div>
</div>
@endsection
