@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Brands</h2>
        <a href="{{ route('brands.create') }}" class="btn btn-dark">Add Brand</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('brands.index') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="searchBrands" name="search" placeholder="Search brands..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                            {{ substr($brand->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $brand->name }}</div>
                                        <div class="text-muted small">{{ $brand->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $brand->products_count ?? 0 }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('brands.show', $brand) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this brand?')">
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
                            <td colspan="4" class="text-center py-4">No brands found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $brands->firstItem() ?? 0 }}-{{ $brands->lastItem() ?? 0 }} of {{ $brands->total() ?? 0 }} brands
                </div>
                <div>
                    {{ $brands->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
