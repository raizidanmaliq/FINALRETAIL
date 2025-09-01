<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/" style="font-weight: bold; color: #A34A4A;">Ahlinya Retail</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sale</a>
                </li>
            </ul>
        </div>
        <div class="d-flex align-items-center">
            <a href="#" class="nav-link me-3"><i class="fas fa-shopping-cart"></i></a>
            <a href="{{ route('customer.auth.login.index') }}" class="btn btn-outline-secondary me-2" style="border-radius: 20px;">Sign In</a>
            <a href="{{ route('customer.auth.registers.index') }}" class="btn btn-danger" style="background-color: #A34A4A; border: none; border-radius: 20px;">Register</a>
        </div>
    </div>
</nav>
