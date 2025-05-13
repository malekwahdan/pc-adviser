@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Category Details</h2>
        <div>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary me-2">Edit</a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Back to Categories</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                    @endif
                    <h3>{{ $category->name }}</h3>
                    <p class="text-muted">{{ $category->slug }}</p>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Description</h6>
                        <p>{{ $category->description ?? 'No description available' }}</p>
                    </div>

                

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Created At</h6>
                            <p>{{ $category->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Last Updated</h6>
                            <p>{{ $category->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Products in this Category</h5>
            <span class="badge bg-primary">{{ $category->products->count() }}</span>
        </div>
        <div class="card-body">
            @if($category->products->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
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
            @else
                <p class="text-muted text-center py-3">No products found in this category</p>
            @endif
        </div>
    </div>
</div>
@endsection
