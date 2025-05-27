<!-- resources/views/products/show.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="row mb-3">
        <div class="col">
            <h2>Product Details</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary ms-2">
                <i class="bi bi-pencil"></i> Edit Product
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-fluid mb-3" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <p class="text-muted">No image available</p>
                        </div>
                    @endif

                    <h3 class="card-title">{{ $product->name }}</h3>
                    <p class="text-muted">{{ $product->brand->name }}</p>

                    <div class="d-flex justify-content-center align-items-center mb-3">
                        @if($product->sale_price)
                            <span class="text-decoration-line-through text-muted me-2">JOD{{ number_format($product->price, 2) }}</span>
                            <span class="fs-4 text-danger">JOD{{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span class="fs-4">JOD{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $product->status === 'in_stock' ? 'success' : ($product->status === 'out_of_stock' ? 'warning' : 'danger') }} me-2">
                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        </span>

                        @if($product->featured)
                            <span class="badge bg-info">Featured</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Category</p>
                            <p class="fw-bold">{{ $product->category?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Stock Quantity</p>
                            <p class="fw-bold">{{ $product->stock_quantity }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Product Cost</p>
                            <p class="fw-bold">JOD{{ number_format($product->cost, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Regular Price</p>
                            <p class="fw-bold">JOD{{ number_format($product->price, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Sale Price</p>
                            <p class="fw-bold">{{ $product->sale_price ? 'JOD'.number_format($product->sale_price, 2) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Profit Margin</p>
                            <p class="fw-bold">{{ number_format($product->getProfitMarginAttribute(), 2) }}%</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Created At</p>
                            <p class="fw-bold">{{ $product->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Last Updated</p>
                            <p class="fw-bold">{{ $product->updated_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Product ID</p>
                            <p class="fw-bold">{{ $product->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
