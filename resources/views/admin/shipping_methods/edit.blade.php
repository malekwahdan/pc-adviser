@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Shipping Method</h2>
        <a href="{{ route('shipping-methods.index') }}" class="btn btn-outline-dark">Back to List</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('shipping-methods.update', $shippingMethod) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shippingMethod->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $shippingMethod->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $shippingMethod->price) }}" step="0.01" min="0" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="estimated_delivery_time" class="form-label">Estimated Delivery Time</label>
                    <input type="text" class="form-control @error('estimated_delivery_time') is-invalid @enderror" id="estimated_delivery_time" name="estimated_delivery_time" value="{{ old('estimated_delivery_time', $shippingMethod->estimated_delivery_time) }}" placeholder="e.g. 2-3 business days">
                    @error('estimated_delivery_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">Update Shipping Method</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
