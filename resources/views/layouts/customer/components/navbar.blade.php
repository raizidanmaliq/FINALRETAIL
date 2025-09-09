<nav class="main-header navbar navbar-expand navbar-secondary-dark navbar-light bg-white">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-secondary" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        {{-- Tombol Keranjang Belanja --}}
        <li class="nav-item">
            <a class="nav-link text-secondary" href="{{ route('customer.carts.index') }}">
                <i class="fas fa-shopping-cart"></i>
                @php
                    // Ambil jumlah item keranjang dari database jika login, atau dari session jika tamu.
                    $cartCount = 0;
                    if (auth()->guard('customer')->check()) {
                        $cartCount = auth()->guard('customer')->user()->carts()->sum('quantity');
                    } elseif (session()->has('cart')) {
                        $cartCount = array_sum(array_column(session('cart'), 'quantity'));
                    }
                @endphp
                <span class="badge badge-warning navbar-badge">{{ $cartCount }}</span>
            </a>
        </li>

        {{-- Tautan untuk Pengguna yang Belum Login --}}
        @guest('customer')
            <li class="nav-item">
                <a href="{{ route('customer.auth.login.index') }}" class="nav-link text-secondary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customer.auth.registers.index') }}" class="nav-link text-secondary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </li>
        @endguest

        {{-- Tautan untuk Pengguna yang Sudah Login --}}
        @auth('customer')
            <li class="nav-item dropdown">
                <a class="nav-link text-secondary" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i> {{ auth()->guard('customer')->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ route('customer.profiles.index') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="dropdown-item">
                        <i class="fas fa-box mr-2"></i> Pesanan Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('customer.auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endauth
    </ul>
</nav>
