<aside class="main-sidebar sidebar-dark-secondary elevation-4">
    <a href="#!" class="brand-link">
        <img src="https://png.pngtree.com/png-clipart/20210312/original/pngtree-letter-v-logo-png-image_6074191.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light text-white">Binco Talent Talent</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('front.home') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Halaman Utama</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('talent.schedule.index') }}" class="nav-link {{ Route::is('talent.schedule.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Jadwal Order</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
