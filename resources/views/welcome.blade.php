@extends('layouts.public')
@section('content')


<section class="banner" style="position: relative; background-image: url('https://images.pexels.com/photos/7720712/pexels-photo-7720712.jpeg'); background-size: cover; background-position: center; height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5);"></div>
    <div class="container text-center text-white position-relative">
        <h1 class="display-5 fw-bold mb-3">Find the Right PC for Your Needs</h1>
        <p class="lead mb-4">Not sure what to buy? Whether you're gaming, working, or creating — we’ll match you with the perfect PC that fits your purpose and budget.</p>
        <a href="{{ route('p.index') }}" class="btn btn-dark px-4 py-2">Shop Now</a>
    </div>
</section>

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

    <!-- Categories -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4">Top Categories</h2>
            <div class="row g-4">
                @forelse($featuredCategories as $category)
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('p.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            <div class="category-card text-center">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid mb-3" style="height: 150px; object-fit: contain;">
                                @else
                                    <div class="category-icon">
                                        <i class="bi bi-grid" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <h5 style="color: #212529">{{ $category->name }}</h5>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No featured categories available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Featured Products</h2>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top p-3" alt="{{ $product->name }}" style="height: 200px; object-fit: contain;">
                        @else
                            <div class="placeholder-img p-3">Product Image</div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($product->name, 36) }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($product->sale_price)
                                    <div>
                                        <span class="text-decoration-line-through text-muted">{{ number_format($product->price, 2) }}</span>
                                        <span class="h5 mb-0 text-danger">JOD {{ number_format($product->sale_price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="h5 mb-0">JOD {{ number_format($product->price, 2) }}</span>
                                @endif
                                <a href="{{ route('p.show', $product->slug) }}" class="btn btn-dark">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No featured products available.</p>
                </div>

            @endforelse

        </div>
    </div>

</section>
<!-- About Us Section -->
<section class="py-5" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="mb-4">About Us</h2>
                <p class="lead mb-4">At Pc-Adviser , we believe buying the perfect PC shouldn’t be overwhelming. That’s why we built an AI-powered platform that understands your needs — whether you’re a gamer, a creator, a student, or a professional — and helps you choose the right computer with confidence</p>
                <p class="mb-4">Our intelligent recommendation system analyzes your preferences and matches you with the most suitable options from our curated collection of PCs and components. No more guesswork. No more tech jargon. Just smart suggestions, tailored for you.</p>
                <p class="mb-4">We're passionate about making technology accessible, reliable, and personalized. Join us and experience a new way to shop for your next PC — powered by AI, backed by expertise.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-dark">Contact Us</a>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image rounded shadow">
                    <img src="https://images.pexels.com/photos/356056/pexels-photo-356056.jpeg" alt="Our workshop" class="img-fluid rounded">
                </div>
            </div>
        </div>



    </div>
</section>




    @endsection



