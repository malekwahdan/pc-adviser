{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.public')


<style>
    .product-image {
        width: 80px;       /* Reduced from 100px */
        height: 80px;      /* Reduced from 100px */
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 4px;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Changed to contain to prevent stretching */
    }

    .quantity-control {
        display: flex;
        align-items: center;
    }

    .quantity-control input {
        width: 40px;
        text-align: center;
        border: none;
    }

    .quantity-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        padding: 0 8px;
    }

    .recommended-product {
        transition: transform 0.3s;
    }

    .recommended-product:hover {
        transform: translateY(-5px);
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .product-image {
            width: 60px;
            height: 60px;
        }
    }

    /* Fix for recommended product images */
    .recommended-image {
        height: 120px;
        width: 120px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        overflow: hidden;
        border-radius: 4px;
    }

    .recommended-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>


@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="checkout-steps">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-title">Cart</div>
                    </div>

                    <div class="step-line active"></div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-title">Shipping/Payment</div>
                    </div>

                    <div class="step-line"></div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-title">Confirmation</div>
                    </div>

                    <div class="step-line"></div>

                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-title">Success</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cart Items Column -->
        <div class="col-lg-8 mb-4">
            <h2 class="mb-4">Shopping Cart ({{ $cartItems->count() }})</h2>

            @if($cartItems->count() > 0)
                @foreach($cartItems as $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-4 mb-3 mb-md-0">
                                    <div class="product-image">
                                        @if($item->product->thumbnail)
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}">
                                        @else
                                            <span class="small">Product {{ $loop->iteration }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-8 mb-3 mb-md-0">
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-0 small">
                                        @if(isset($item->product->attributes['size'])) Size: {{ $item->product->attributes['size'] }} | @endif
                                        @if(isset($item->product->attributes['color'])) Color: {{ $item->product->attributes['color'] }} @endif
                                    </p>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="quantity-control">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                            <button type="submit" class="quantity-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        </form>

                                        <input type="text" value="{{ $item->quantity }}" readonly>

                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                            <button type="submit" class="quantity-btn" {{ $item->quantity >= $item->product->stock_quantity ? 'disabled' : '' }}>+</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2 col-3 text-end">
                                    <span class="fw-bold">${{ number_format($item->product->getCurrentPrice() * $item->quantity, 2) }}</span>
                                </div>
                                <div class="col-md-1 col-3 text-end">
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    Your cart is empty. <a href="{{ route('p.index') }}">Continue shopping</a>
                </div>
            @endif
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tax</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold">${{ number_format($total, 2) }}</span>
                    </div>

                    @if($cartItems->count() > 0)
                        <a href="{{ route('checkout.index') }}" class="btn btn-dark w-100">Proceed to Checkout</a>
                    @else
                        <button class="btn btn-dark w-100" disabled>Proceed to Checkout</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Products -->
    @if($recommendedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">You Might Also Like</h3>
            <div class="row">
                @foreach($recommendedProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 recommended-product">
                            <div class="card-body text-center">
                                <div class="recommended-image">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                                    @else
                                        <span class="small">Product Image</span>
                                    @endif
                                </div>
                                <h5 class="mb-1">{{ Str::limit($product->name, 33) }}</h5>
                                <p class="text-muted small mb-2">{{ Str::limit($product->description, 40) }}</p>
                                <p class="fw-bold mb-3">${{ number_format($product->getCurrentPrice(), 2) }}</p>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-dark w-100">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
