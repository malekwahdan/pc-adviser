<!-- Navbar -->
<nav class="navbar navbar-expand-md mobile-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mobileNavMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i> Products
                    </a>
                </li>
                @if (Auth::guard('admin')->user()->role === 'super_admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.index') }}">
                        <i class="fas fa-chart-bar"></i> Admins
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}">
                        <i class="fas fa-cog"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('brands.index') }}">
                        <i class="fas fa-cog"></i> Brands
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.index') }}">
                        <i class="fas fa-cog"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reviews.index') }}">
                        <i class="fas fa-cog"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shipping-methods.index') }}">
                        <i class="fas fa-cog"></i> Shipping
                    </a>
                </li>
             
                {{-- <li class="nav-item">

                        <i class="fas fa-cog"></i> <form class="nav-link" method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link" style="padding: 0; margin: 0; border: none; background: none;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>


            </ul>

        </div>
    </div>
</nav>
<!-- End Navbar -->
