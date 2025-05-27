
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
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Filters</h5>
                    <form action="{{ route('p.index') }}" method="GET" id="filter-form">
                        <!-- Search Bar -->
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search products..." value="{{ request('search') }}">
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label for="price-range" class="form-label">Price Range</label>
                            <div class="d-flex justify-content-between mb-2">
                                <span>0</span>
                                <span>{{ $maxPrice ?? 5000 }}</span>
                            </div>
                            <input type="range" class="form-range" id="price-range" name="max_price" min="0" max="{{ $maxPrice ?? 5000 }}" value="{{ request('max_price', $maxPrice ?? 5000) }}">
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" name="brands[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}"
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="brand-{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
                        <a href="{{ route('p.index') }}" class="btn btn-outline-secondary w-100 mt-2">Clear All</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Listing -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Products</h4>
                    <p class="text-muted mb-0">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
                </div>
                <form action="{{ route('p.index') }}" method="GET" class="d-flex">
                   
                    @if(request()->has('brands'))
                        @foreach(request('brands') as $brandId)
                            <input type="hidden" name="brands[]" value="{{ $brandId }}">
                        @endforeach
                    @endif

                    @if(request()->has('categories'))
                        @foreach(request('categories') as $categoryId)
                            <input type="hidden" name="categories[]" value="{{ $categoryId }}">
                        @endforeach
                    @endif

                    @if(request()->has('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @endif

                    <!-- Search bar next to dropdown -->
                    <div class="input-group me-2 d-none d-md-flex" style="width: 350px;">
                        <input type="text" class="form-control" placeholder="Quick search" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Sort dropdown -->
                    <select class="form-select" name="sort" onchange="this.form.submit()">
                        <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by Popularity</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    </select>
                </form>
            </div>



            <!-- Products Grid -->
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 product-card">
                            <div class="text-center bg-light p-3">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-fluid" style="height: 150px; object-fit: contain;">
                                @else
                                    <div class="placeholder-img d-flex align-items-center justify-content-center" style="height: 150px;">
                                        {{ $product->name }} Image
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($product->name, 44) }}</h5>
                                <p class="card-text small text-muted">{{ Str::limit($product->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($product->sale_price)
                                        <div>
                                            <span class="text-decoration-line-through text-muted">JOD{{ number_format($product->price, 2) }}</span>
                                            <span class="fw-bold text-danger">JOD {{ number_format($product->sale_price, 2) }}</span>
                                        </div>
                                    @else
                                        <span class="fw-bold">JOD {{ number_format($product->price, 2) }}</span>
                                    @endif
                                    <a href="{{ route('p.show', $product->slug) }}" class="btn btn-sm btn-outline-dark">View</a>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                                    <button type="submit" class="btn btn-dark w-100">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            No products found matching your criteria.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">

                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>



@endsection
