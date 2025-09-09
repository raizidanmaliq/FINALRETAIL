<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="/" style="color: #A34A4A;">Ahlinya Retail</a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu & Buttons -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu Tengah -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3 text-center">
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium text-danger" href="#">Sale</a>
                </li>
            </ul>

            <!-- Cart & Auth Buttons -->
            <div class="d-flex align-items-center justify-content-center ms-lg-auto mt-3 mt-lg-0">
                <a href="#" class="nav-link me-3">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="{{ route('customer.auth.login.index') }}"
                   class="btn btn-outline-secondary me-2 rounded-pill">
                    Sign In
                </a>
                <a href="{{ route('customer.auth.registers.index') }}"
                   class="btn text-white rounded-pill"
                   style="background-color: #A34A4A;">
                    Register
                </a>
            </div>
        </div>
    </div>
</nav>
