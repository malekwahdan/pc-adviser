@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Shipping Methods</h2>
        <a href="{{ route('shipping-methods.create') }}" class="btn btn-dark">Add Shipping Method</a>
    </div>

    <div class="card">
        <div class="card-body">
          

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Est. Delivery Time</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethods as $method)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $method->name }}</div>
                                <div class="text-muted small">{{ Str::limit($method->description, 50) ?? 'N/A' }}</div>
                            </td>
                            <td>{{ number_format($method->price, 2) }}</td>
                            <td>{{ $method->estimated_delivery_time ?? 'N/A' }}</td>

                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('shipping-methods.edit', $method) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('shipping-methods.show', $method) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('shipping-methods.destroy', $method) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shipping method?')">
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
                            <td colspan="5" class="text-center py-4">No shipping methods found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">


            </div>
        </div>
    </div>
</div>
@endsection
