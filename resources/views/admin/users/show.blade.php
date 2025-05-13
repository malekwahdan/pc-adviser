@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">User Details</h2>
        <div>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2">Edit User</a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="card-title">{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>

                    <div class="d-flex justify-content-center mt-3">
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete User</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="small text-muted">
                        <div>Account created: {{ $user->created_at->format('M d, Y') }}</div>
                        <div>Last updated: {{ $user->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email</div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Phone</div>
                        <div class="col-md-9">{{ $user->phone ?? 'Not provided' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Address</div>
                        <div class="col-md-9">{{ $user->address ?? 'Not provided' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">City</div>
                        <div class="col-md-9">{{ $user->city ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Security</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email Verification</div>
                        <div class="col-md-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">Not verified</span>
                                <a href="#" class="btn btn-sm btn-outline-primary ms-2">Send verification email</a>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 fw-bold">Password</div>
                        <div class="col-md-9">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Reset password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
