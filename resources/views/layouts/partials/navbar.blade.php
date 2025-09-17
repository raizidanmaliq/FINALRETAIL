<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3" style="border-color: #E0CFCF;">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fs-4 fw-semibold" href="/" style="color: #A34A4A;">
            Ahlinya Retail
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Menu -->
            <ul class="navbar-nav mx-auto gap-3">
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('front.catalog.index', ['gender' => 'Wanita']) }}">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('front.catalog.index', ['gender' => 'Pria']) }}">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('front.catalog.index') }}">Sale</a>
                </li>
            </ul>

            <!-- Right Content -->
            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <!-- Search -->
                <form class="d-flex position-relative me-2" style="max-width: 220px;" action="{{ route('front.catalog.index') }}" method="GET">
                    <input class="form-control form-control-sm rounded-pill pe-5"
                        type="search" placeholder="Search for products" name="search"
                        style="border: 1px solid #A34A4A;">
                    <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0" type="submit"
                        style="background: transparent; border: none;">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                </form>

                <!-- Cart -->
                <a href="{{ url('customer/carts') }}" class="nav-link text-dark fs-5 me-2">
                    <i class="fas fa-shopping-cart"></i>
                </a>

                <!-- Auth -->
                @guest('customer')
                    <a href="{{ route('customer.auth.login.index') }}"
                       class="btn btn-sm fw-semibold px-3 rounded-pill"
                       style="background-color: #fff; color:#A34A4A; border:1px solid #A34A4A;">
                        Sign In
                    </a>
                    <a href="{{ route('customer.auth.registers.index') }}"
                       class="btn btn-sm text-white fw-semibold px-3 rounded-pill"
                       style="background-color: #A34A4A;">
                        Register
                    </a>
                @endguest

                @auth('customer')
                    <div class="dropdown">
                        <a class="nav-link text-dark fs-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow mt-2" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('customer/profiles') }}"><i class="fas fa-user me-2 text-muted"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ url('customer/orders') }}"><i class="fas fa-box me-2 text-muted"></i> Riwayat Pemesanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#"
                                   style="color:#A34A4A;"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2" style="color:#A34A4A;"></i> Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('customer.auth.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
