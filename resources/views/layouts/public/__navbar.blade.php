<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Pc-Adviser </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('p.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/#about') }}">About</a>
                </li>
            </ul>

            <!-- Desktop view (dropdown for user options) -->
            <div class="ms-3 d-none d-lg-flex">
                <!-- Always visible cart icon -->
                <a href="{{ route('cart.index') }}" class="btn btn-dark me-2" style="background-color: #343a40;">
                    <i class="bi bi-cart"></i>
                </a>

                <!-- Only visible when logged in -->
                @auth
                <a href="{{ route('wishlist.index') }}" class="btn btn-dark me-2" style="background-color: #343a40;">
                    <i class="bi bi-heart"></i>
                </a>
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #343a40;">
                        <i class="bi bi-person"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2" style="background-color: #343a40; border-color: #343a40; color: white;">Login</a>
                <a href="{{ route('register') }}" class="btn btn-dark" style="color: white !important;">Register</a>
                @endauth
            </div>

            <!-- Mobile view (list items for all options) -->
            <ul class="navbar-nav d-lg-none mt-2">
                <!-- Cart always visible -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart me-2"></i>Cart
                    </a>
                </li>

                @auth
                <!-- User options as list items -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-heart me-2"></i>Wishlist
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.index') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link bg-transparent border-0 w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-2"></i>Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
