@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Order #{{ $order->order_number }}</h2>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order Details</h5>
                    <div>
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="fw-bold">Order Date</h6>
                            <p>{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Status</h6>
                            <span class="badge bg-{{
                                $order->status == 'pending' ? 'warning' :
                                ($order->status == 'processing' ? 'info' :
                                ($order->status == 'shipped' ? 'primary' :
                                ($order->status == 'delivered' ? 'success' : 'danger')))
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Payment Status</h6>
                            <span class="badge bg-{{
                                $order->payment_status == 'paid' ? 'success' :
                                ($order->payment_status == 'pending' ? 'warning' : 'danger')
                            }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <h6 class="fw-bold mb-3">Order Items</h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->thumbnail)
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            @else
                                            <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                                {{ substr($item->product->name, 0, 1) }}
                                            </div>
                                            @endif

                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td>${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->tax > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tax:</td>
                                    <td>${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping:</td>
                                    <td>${{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                @endif
                                @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Discount:</td>
                                    <td>-${{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Customer Name</h6>
                        <p>{{ $order->user?->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Email</h6>
                        <p>{{ $order->user?->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Phone</h6>
                        <p>{{ $order->user?->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p>{{ $order->user?->city ?? 'N/A' }}</p>

                    <p>{{ $order->shipping_address }}</p>

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Payment Method</h6>
                        <p>{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Transaction ID</h6>
                        <p>{{ $order->payment->transaction_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            @if($order->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Notes</h5>
                </div>
                <div class="card-body">
                    <p>{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
