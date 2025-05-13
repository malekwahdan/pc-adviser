<div class="col-md-3 col-lg-2 p-0 sidebar">
    <div class="d-flex flex-column p-3">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 text-white text-decoration-none">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span class="fs-4">Admin Panel</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fas fa-box"></i>
                    Products
                </a>
            </li>
            @if (Auth::guard('admin')->user()->role === 'super_admin')
            <li>
                <a href="{{ route('admins.index') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    admins
                </a>
            </li>
            @endif

            <li>
                <a href="{{ route('categories.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Categories
                </a>
            </li>
            <li>
                <a href="{{ route('brands.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Brands
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Orders
                </a>
            </li>
            <li>
                <a href="{{ route('reviews.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Reviews
                </a>
            </li>
            <li>
                <a href="{{ route('shipping-methods.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Shipping
                </a>
            </li>
         
            <li>
                <form method="POST" action="{{ route('admin.logout') }}" class="nav-link p-0">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link w-100 d-flex align-items-center" style="text-align: left; padding: 8px 16px;">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
