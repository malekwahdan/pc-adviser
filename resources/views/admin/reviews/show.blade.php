@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Review Details</h2>
        <div>
            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">Back to Reviews</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Review Information</h5>
                    <div>
                        <form action="{{ route('reviews.updateStatus', $review) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="fw-bold mb-0 me-3">{{ $review->title }}</h5>
                            <div class="text-warning d-flex align-items-center">
                                <span class="fw-bold me-2">{{ $review->rating }}/5</span>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} me-1"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="border-bottom pb-3 mb-3">
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Review Date</h6>
                            <p>{{ $review->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Status</h6>
                            <span class="badge bg-{{
                                $review->status == 'pending' ? 'warning' :
                                ($review->status == 'approved' ? 'success' : 'danger')
                            }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($review->product->image)
                            <img src="{{ asset('storage/' . $review->product->image) }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ substr($review->product->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="text-center mb-3">{{ $review->product->name }}</h5>

                    <div class="mb-3">
                        <h6 class="fw-bold">Price</h6>
                        <p>${{ number_format($review->product->price, 2) }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Category</h6>
                        <p>{{ $review->product->category->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Average Rating</h6>
                        <div class="d-flex align-items-center">
                            <div class="text-warning me-2">
                                @php
                                    $avgRating = $review->product->reviews()->where('status', 'approved')->avg('rating') ?? 0;
                                    $avgRating = round($avgRating, 1);
                                @endphp
                                <span class="fw-bold">{{ $avgRating }}/5</span>
                            </div>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($avgRating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($i - 0.5 <= $avgRating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-muted small mt-1">
                            Based on {{ $review->product->reviews()->where('status', 'approved')->count() }} reviews
                        </p>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('products.show', $review->product) }}" class="btn btn-outline-primary">
                            View Product
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Name</h6>
                        <p>{{ $review->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Email</h6>
                        <p>{{ $review->user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Total Reviews</h6>
                        <p>{{ $review->user->reviews()->count() }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Member Since</h6>
                        <p>{{ $review->user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
