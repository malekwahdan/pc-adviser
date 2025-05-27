{{-- resources/views/wishlist/index.blade.php --}}
@extends('layouts.public')

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

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Wishlist ({{ $wishlistItems->count() }})</h1>
        @if($wishlistItems->count() > 0)
            <form action="{{ route('wishlist.clear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-trash"></i> Clear All
                </button>
            </form>
        @endif
    </div>

    @if($wishlistItems->count() > 0)
        <div class="row">
            @foreach($wishlistItems as $item)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <div class="bg-light text-center py-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                @if($item->product->thumbnail)
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-height: 180px; max-width: 90%;">
                                @else
                                    <div class="text-muted">{{ $item->product->name }} Image</div>
                                @endif
                            </div>
                            <form action="{{ route('wishlist.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm position-absolute end-0 top-0 m-2 text-danger bg-white rounded-circle" style="width: 30px; height: 30px; line-height: 1;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($item->product->name, 40) }}</h5>

                            <p class="fw-bold mb-2">JOD {{ number_format($item->product->price, 2) }}</p>
                            <p class="mb-3">
                                @if($item->product->stock_quantity > 0)
                                    <span class="text-success">In Stock</span>
                                @else
                                    <span class="text-danger">Out of Stock</span>
                                @endif
                            </p>

                            @if($item->product->stock_quantity > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-dark w-100">Add to Cart</button>
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                </form>
                            @else
                                <button class="btn btn-secondary w-100">Notify When Available</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-heart" style="font-size: 3rem;"></i>
            </div>
            <h4>Your wishlist is empty</h4>
            <p class="text-muted">Add items you love to your wishlist. Review them anytime and easily move them to the cart.</p>
            <a href="{{ route('p.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    @endif
</div>
@endsection
