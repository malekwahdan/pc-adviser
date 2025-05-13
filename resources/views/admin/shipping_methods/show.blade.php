@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Shipping Method Details</h2>
        <div>
            <a href="{{ route('shipping-methods.edit', $shippingMethod) }}" class="btn btn-primary me-2">Edit</a>
            <a href="{{ route('shipping-methods.index') }}" class="btn btn-outline-dark">Back to List</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="card-title">{{ $shippingMethod->name }}</h3>
                    
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="text-muted">Created: {{ $shippingMethod->created_at->format('M d, Y') }}</div>
                    <div class="text-muted">Last Updated: {{ $shippingMethod->updated_at->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5>Shipping Price</h5>
                        <p class="fs-4">${{ number_format($shippingMethod->price, 2) }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5>Estimated Delivery Time</h5>
                        <p>{{ $shippingMethod->estimated_delivery_time ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5>Description</h5>
                <p>{{ $shippingMethod->description ?? 'No description provided.' }}</p>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <form action="{{ route('shipping-methods.destroy', $shippingMethod) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shipping method?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
                <div>
                    <a href="{{ route('shipping-methods.edit', $shippingMethod) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
