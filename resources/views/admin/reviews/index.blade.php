@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Product Reviews</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reviews.index') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchReviews" name="search" placeholder="Search reviews..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="rating" class="form-select">
                            <option value="">All Ratings</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($review->product->image)
                                    <img src="{{ asset('storage/' . $review->product->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                    @else
                                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                        {{ substr($review->product->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <span>{{ Str::limit($review->product->name, 30) }}</span>
                                </div>
                            </td>
                            <td>{{ $review->user?->name ?? 'N/A' }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2 fw-bold">{{ $review->rating }}/5</span>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>{{ Str::limit($review->title, 25) }}</td>
                            <td>
                                <form action="{{ route('reviews.updateStatus', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No reviews found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $reviews->firstItem() ?? 0 }}-{{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() ?? 0 }} reviews
                </div>
                <div>
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
