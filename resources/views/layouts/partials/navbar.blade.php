<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3" style="border-color: #E0CFCF;">
    <div class="container">
        <a class="navbar-brand fs-4" href="/" style="font-weight: 600; color: #A34A4A;">
            Ahlinya Retail
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav gap-4">
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium fs-5 py-2" href="{{ route('front.catalog.index', ['category' => 'Perempuan']) }}">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium fs-5 py-2" href="{{ route('front.catalog.index', ['category' => 'Laki-Laki']) }}">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium fs-5 py-2" href="{{ route('front.catalog.index') }}">Sale</a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <a href="{{ url('customer/carts') }}" class="nav-link me-3 text-dark fs-5">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <span class="text-muted mx-2">|</span>

            {{-- Jika belum login --}}
            @guest('customer')
                <a href="{{ route('customer.auth.login.index') }}" class="nav-link me-2 text-dark fs-5">Sign In</a>
                <a href="{{ route('customer.auth.registers.index') }}"
                   class="btn btn-sm text-white px-3 py-2 fs-6"
                   style="background-color: #A34A4A; border-radius: 20px;">
                    Register
                </a>
            @endguest

            {{-- Jika sudah login --}}
            @auth('customer')
                <a href="{{ url('customer/profiles') }}"
                   class="btn btn-sm text-white px-3 py-2 fs-6"
                   style="background-color: #A34A4A; border-radius: 20px;">
                    Profile
                </a>
            @endauth
        </div>
    </div>
</nav>
