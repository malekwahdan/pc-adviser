@extends('layouts.admin')
@section('content')
    <!-- Main Content Area -->
    <div class="col-md-9 col-lg-10 main-content">
        <!-- Top Search Bar -->
        <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Search...">
            </div>
            <div>
                <a href="#" class="btn btn-link text-dark me-2"><i class="fas fa-bell"></i></a>
                <a href="#" class="btn btn-link text-dark"><i class="fas fa-user-circle"></i></a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h4 class="mb-0">2,543</h4>
                        </div>
                        <div class="stats-icon stats-users">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Revenue</p>
                            <h4 class="mb-0">$45,234</h4>
                        </div>
                        <div class="stats-icon stats-revenue">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Orders</p>
                            <h4 class="mb-0">1,123</h4>
                        </div>
                        <div class="stats-icon stats-orders">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Products</p>
                            <h4 class="mb-0">456</h4>
                        </div>
                        <div class="stats-icon stats-products">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Revenue Overview</h5>
                <div class="chart-container">
                    <!-- Placeholder for revenue chart -->
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        Revenue chart would appear here
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h5>User Activity</h5>
                <div class="chart-container">
                    <!-- Placeholder for user activity chart -->
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        User activity chart would appear here
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="row">
            <div class="col-12">
                <h5>Recent Activity</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table activity-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/32/32" alt="John Doe" class="me-2">
                                                <span>John Doe</span>
                                            </div>
                                        </td>
                                        <td>Created new post</td>
                                        <td>Apr 17, 2025</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/32/32" alt="Jane Smith" class="me-2">
                                                <span>Jane Smith</span>
                                            </div>
                                        </td>
                                        <td>Updated profile</td>
                                        <td>Apr 16, 2025</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/api/placeholder/32/32" alt="Mike Johnson" class="me-2">
                                                <span>Mike Johnson</span>
                                            </div>
                                        </td>
                                        <td>Deleted comment</td>
                                        <td>Apr 15, 2025</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
