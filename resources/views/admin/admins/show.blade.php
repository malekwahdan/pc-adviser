@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Admin Details</h2>
        <div>
            <a href="{{ route('admins.edit', $admin) }}" class="btn btn-primary me-2">Edit Admin</a>
            <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">Back to Admins</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($admin->name, 0, 1) }}
                    </div>
                    <h3 class="card-title">{{ $admin->name }}</h3>
                    <p class="text-muted">{{ $admin->email }}</p>

                    <div class="my-3">
                        <span class="badge bg-{{ $admin->role === 'super_admin' ? 'danger' : 'primary' }} p-2 fs-6">
                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete Admin</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="small text-muted">
                        <div>Account created: {{ $admin->created_at->format('M d, Y') }}</div>
                        <div>Last updated: {{ $admin->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Name</div>
                        <div class="col-md-9">{{ $admin->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email</div>
                        <div class="col-md-9">{{ $admin->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Role</div>
                        <div class="col-md-9">
                            <span class="badge bg-{{ $admin->role === 'super_admin' ? 'danger' : 'primary' }}">
                                {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                            </span>
                        </div>
                    </div>


                </div>
            </div>

           
        </div>
    </div>
</div>
@endsection
