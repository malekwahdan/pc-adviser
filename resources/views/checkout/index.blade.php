@extends('layouts.public')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Checkout Steps -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="checkout-steps">
                            <div class="step active">
                                <div class="step-number">1</div>
                                <div class="step-title">Cart</div>
                            </div>

                            <div class="step-line active"></div>

                            <div class="step active">
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

            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row g-4">
                    <!-- Left Column - Customer Information -->
                    <div class="col-lg-7">
                        <!-- Shipping Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="firstName" name="first_name" value="{{ old('first_name', $user->name ?? '') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="street" class="form-label">Address</label>
                                        <input type="text" class="form-control @error('street_address') is-invalid @enderror" id="street" name="street_address" value="{{ old('street_address', $user->address ?? '') }}" required>
                                        @error('street_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->city ?? '') }}" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sameAddress" name="billing_same">
                                            <label class="form-check-label" for="sameAddress">
                                                Billing address is the same
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Shipping Method</h5>
                            </div>
                            <div class="card-body">
                                @foreach($shippingMethods as $method)
                                <div class="form-check mb-3">
                                    <input class="form-check-input shipping-method" type="radio" name="shipping_method" id="shipping{{ $method->id }}" value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }} data-price="{{ $method->price }}">
                                    <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="shipping{{ $method->id }}">
                                        <div>
                                            <strong>{{ $method->name }}</strong>
                                            <p class="text-muted mb-0 small">{{ $method->estimated_delivery_time }}</p>
                                        </div>
                                        <span>${{ number_format($method->price, 2) }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Payment Method</h5>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div class="mb-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input payment-method" type="radio" name="payment_method" id="creditCard" value="card" checked>
                                        <label class="form-check-label d-flex align-items-center" for="creditCard">
                                            <i class="bi bi-credit-card me-2"></i> Credit Card (Stripe)
                                        </label>
                                    </div>

                                    <div class="card-payment-info small text-muted mt-2">
                                        <p class="mb-0">You'll be redirected to Stripe to complete your payment securely.</p>
                                    </div>
                                </div>

                                <!-- Other Payment Methods -->
                                <div class="border-top pt-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input payment-method" type="radio" name="payment_method" id="paypal" value="paypal">
                                        <label class="form-check-label d-flex align-items-center" for="paypal">
                                            <i class="bi bi-paypal me-2 text-primary"></i> PayPal
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input payment-method" type="radio" name="payment_method" id="googlePay" value="google_pay">
                                        <label class="form-check-label d-flex align-items-center" for="googlePay">
                                            <i class="bi bi-google me-2 text-success"></i> Google Pay
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input payment-method" type="radio" name="payment_method" id="applePay" value="apple_pay">
                                        <label class="form-check-label d-flex align-items-center" for="applePay">
                                            <i class="bi bi-apple me-2"></i> Apple Pay
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="col-lg-5">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Order Summary</h5>
                            </div>

                            <div class="card-body">
                                <!-- Products List -->
                                @foreach($formattedCartItems as $item)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" width="60" height="60" class="img-thumbnail">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Qty: {{ $item['quantity'] }}</span>
                                            <span>${{ number_format($item['price'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach



                                <!-- Order Totals -->
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal</span>
                                        <span id="subtotal">${{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping</span>
                                        <span id="shipping">${{ number_format($shippingCost, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax</span>
                                        <span id="tax">${{ number_format($tax, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 d-none" id="discount-row">
                                        <span>Discount</span>
                                        <span id="discount">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold mb-0">
                                        <span>Total</span>
                                        <span id="total">${{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <button type="submit" class="btn btn-dark w-100 py-2">Place Order</button>
                                <a href="{{ route('cart.index') }}" class="btn btn-link w-100 text-center mt-2">Edit Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const shippingMethods = document.querySelectorAll('.shipping-method');

    shippingMethods.forEach(method => {
        method.addEventListener('change', updateShippingAndTotal);
    });

    function updateShippingAndTotal() {
        const selectedMethod = document.querySelector('.shipping-method:checked');
        const shippingPrice = parseFloat(selectedMethod.dataset.price);

        const shippingElement = document.getElementById('shipping');
        shippingElement.textContent = '$' + shippingPrice.toFixed(2);

        calculateTotal();
    }

    function calculateTotal() {
        const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('$', ''));
        const shipping = parseFloat(document.getElementById('shipping').textContent.replace('$', ''));
        const tax = parseFloat(document.getElementById('tax').textContent.replace('$', ''));

        const total = subtotal + shipping + tax;

        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }
});
</script>
    @endsection
