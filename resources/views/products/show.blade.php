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
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('p.index') }}">Products</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('p.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images Column -->
        <div class="col-lg-6 mb-4">
            <div class="main-product-image mb-3">
                @if(count($product->Images) > 0)
                    <img src="{{ asset('storage/' . $product->Images->first()->image_path) }}" class="img-fluid rounded" id="main-product-img" alt="{{ $product->name }}">
                @else
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" class="img-fluid rounded" id="main-product-img" alt="{{ $product->name }}">
                @endif
            </div>

            <!-- Thumbnail Gallery -->
            @if(count($product->Images) > 0)
                <div class="row g-2">

                    @foreach($product->Images as $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail product-thumbnail" alt="{{ $product->name }}" onclick="changeMainImage(this.src)">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details Column -->
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-start">
                <h1 class="h2 mb-2">{{ $product->name }}</h1>
                <form action="{{ route('wishlist.add') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm" title="Add to wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                </form>

            </div>

            <!-- Ratings -->
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <div class="stars me-2">
                        @php
                            $avgRating = $product->reviews->avg('rating') ?? 0;
                            $filledStars = floor($avgRating);
                            $hasHalfStar = $avgRating - $filledStars >= 0.5;
                        @endphp

                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $filledStars)
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i == $filledStars + 1 && $hasHalfStar)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">({{ $product->reviews->count() }} reviews)</span>
                </div>
            </div>

            <!-- Price -->
            <div class="mb-3">
                @if($product->sale_price)
                    <span class="h3 text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-decoration-line-through text-muted ms-2">${{ number_format($product->price, 2) }}</span>
                    @php
                        $savingsPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                    @endphp
                    <span class="badge bg-danger ms-2">Save {{ $savingsPercent }}%</span>
                @else
                    <span class="h3 fw-bold">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <!-- Status -->
            <div class="mb-3">
                @if($product->stock_quantity > 0)
                    <span class="badge bg-success">In Stock</span>
                    <span class="text-muted ms-2">{{ $product->stock_quantity }} units available</span>
                @elseif($product->status == 'out_of_stock')
                    <span class="badge bg-warning">Out of Stock</span>
                @else
                    <span class="badge bg-secondary">Discontinued</span>
                @endif
            </div>

            <!-- Description -->
<div class="mb-4">
    <h5>Description</h5>
    <p class="text-wrap" style="max-width: 100%; overflow-wrap: break-word;">{{ $product->description }}</p>
</div>

            <!-- Brand -->
            <div class="mb-4">
                <p class="mb-1"><strong>Brand:</strong> {{ $product->brand->name }}</p>

                <!-- Categories -->
                @if($product->category)
                    <p class="mb-1"><strong>Category:</strong> {{ $product->category->name }}</p>
                @endif
            </div>

            <!-- Add to Cart Form -->
@if($product->stock_quantity > 0)
<form action="{{ route('cart.add') }}" method="POST" class="mb-3">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <div class="row">
        <div class="col-md-4 mb-2 mb-md-0">
            <div class="input-group">
                <button type="button" class="btn btn-outline-dark" onclick="decrementQuantity()">-</button>
                <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                <button type="button" class="btn btn-outline-dark" onclick="incrementQuantity({{ $product->stock_quantity }})">+</button>
            </div>
        </div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-dark w-100">Add to Cart</button>
        </div>
    </div>
</form>

                <!-- Quick Action Buttons -->
                <div class="d-flex justify-content-between">

                    <form action="{{ route('cart.buyNow') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-outline-dark flex-grow-1 me-2 w-100">Buy Now</button>
                    </form>


                </div>
            @else
                <div class="alert alert-warning">
                    This item is currently unavailable. Please check back later or browse similar products.
                </div>
            @endif
        </div>
    </div>

    <!-- Tabs for Additional Info -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews ({{ $product->reviews->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping</button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <div class="row">
                        <div class="col-lg-8">


                                <p class="text-wrap" style="max-width: 100%; overflow-wrap: break-word;">{{ $product->description }}</p>

                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <!-- Review Summary -->
                    <div class="mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <span class="display-4">{{ number_format($avgRating, 1) }}</span>
                                <div class="stars my-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $filledStars)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @elseif($i == $filledStars + 1 && $hasHalfStar)
                                            <i class="bi bi-star-half text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted">Based on {{ $product->reviews->count() }} reviews</p>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    @if($product->reviews->count() > 0)
                        <div class="reviews-list">
                            @foreach($product->reviews->where('status', 'approved')->take(5) as $review)
                                <div class="review-item p-3 mb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="https://via.placeholder.com/50" class="rounded-circle" width="50" height="50" alt="{{ $review->user->name }}">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <h6>{{ $review->title }}</h6>
                                    <p>{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if($product->reviews->where('status', 'approved')->count() > 5)
                            <div class="text-center">
                                <button class="btn btn-outline-dark" id="loadMoreReviews">Load More Reviews</button>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            This product has no reviews yet. Be the first to review!
                        </div>
                    @endif

                    <!-- Review Form -->
                    @auth
                        <div class="write-review mt-4">
                            <h5>Write a Review</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <div class="rating-stars">
                                        <div class="btn-group" role="group" aria-label="Rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" autocomplete="off">
                                                <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                                    <i class="bi bi-star-fill"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="mb-3">
                                    <label for="comment" class="form-label">Your Review</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-dark">Submit Review</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            Please <a href="{{ route('login') }}">login</a> to write a review.
                        </div>
                    @endauth
                </div>

                <!-- Shipping Tab -->
                <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    <div class="shipping-methods">
                        <h5 class="mb-4">Available Shipping Methods</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Estimated Delivery</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shippingMethods as $method)
                                        <tr>
                                            <td>{{ $method->name }}</td>
                                            <td>{{ $method->estimated_delivery_time }}</td>
                                            <td>${{ number_format($method->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="shipping-info mt-4">
                            <h5>Shipping Policy</h5>
                            <p>Orders are typically processed within 1-2 business days. Shipping times vary by location and method selected at checkout.</p>
                            <p>Free shipping on orders over $100 within the continental US.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <section class="related-products mt-5">
        <h3 class="mb-4">You May Also Like</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <a href="{{ route('p.show', $relatedProduct->slug) }}">
                            <img src="{{ asset('storage/' . $relatedProduct->thumbnail) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: contain;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('p.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">{{ $relatedProduct->name }}</a>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($relatedProduct->sale_price)
                                    <div>
                                        <span class="text-decoration-line-through text-muted">${{ number_format($relatedProduct->price, 2) }}</span>
                                        <span class="fw-bold text-danger">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                                <a href="{{ route('p.show', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-dark">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>


<script>
    // Function to change main product image
    function changeMainImage(src) {
        document.getElementById('main-product-img').src = src;
    }

    // Increment quantity function
    function incrementQuantity(max) {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < max) {
            input.value = currentValue + 1;
        }
    }

    function decrementQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }

    }
    function changeMainImage(src) {
        document.getElementById('main-product-img').src = src;
    }
</script>

@endsection
