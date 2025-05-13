@extends('layouts.admin')
@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Admins</h2>
        <a href="{{ route('admins.create') }}" class="btn btn-dark">Add Admin</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admins.index') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="searchAdmins" name="search" placeholder="Search admins..." value="{{ request('search') }}">
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
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                           
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $admin->name }}</div>
                                        <div class="text-muted small">ID: {{ $admin->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <span class="badge bg-{{ $admin->role === 'super_admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('admins.show', $admin) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
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
                            <td colspan="5" class="text-center py-4">No admins found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $admins->firstItem() }}-{{ $admins->lastItem() }} of {{ $admins->total() }} admins
                </div>
                <div>
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
