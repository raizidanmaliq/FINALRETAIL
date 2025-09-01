<aside class="main-sidebar sidebar-light elevation-4" style="background-color: #9B4141;">
    <a href="#!" class="brand-link d-flex justify-content-center">
        <span class="brand-text font-weight-light text-white">Ahlinya Retail</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('customer.carts.index') }}"
                       class="nav-link {{ Route::is('customer.carts.index') ? 'active text-danger bg-white' : 'text-white' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Keranjang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.orders.index') }}"
                       class="nav-link {{ Route::is('customer.orders.index') ? 'active text-danger bg-white' : 'text-white' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Pesanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.profiles.index') }}"
                       class="nav-link {{ Route::is('customer.profiles.index') ? 'active text-danger bg-white' : 'text-white' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
