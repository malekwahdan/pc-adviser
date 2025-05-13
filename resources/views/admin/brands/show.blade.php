@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Brand Details</h2>
        <div>
            <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary me-2">Edit</a>
            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">Back to Brands</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                            {{ substr($brand->name, 0, 1) }}
                        </div>
                    @endif
                    <h3>{{ $brand->name }}</h3>
                    <p class="text-muted">{{ $brand->slug }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Brand Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Description</h6>
                        <p>{{ $brand->description ?? 'No description available' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Created At</h6>
                            <p>{{ $brand->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Last Updated</h6>
                            <p>{{ $brand->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Total Products</h6>
                        <p>{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Products by this Brand</h5>
            <span class="badge bg-primary">{{ $brand->products->count() }}</span>
        </div>
        <div class="card-body">
            @if($brand->products->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>
                                        <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($totalProducts > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}" class="btn btn-outline-primary">
                            View All Products
                        </a>
                    </div>
                @endif
            @else
                <p class="text-muted text-center py-3">No products found for this brand</p>
            @endif
        </div>
    </div>
</div>
@endsection
