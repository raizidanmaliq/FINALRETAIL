<aside class="main-sidebar sidebar-dark-secondary elevation-4" style="background-color: #9B4141;">
    <a href="{{ url('/') }}" class="brand-link d-flex justify-content-center">
        <span class="brand-text font-weight-light text-white">Ahlinya Retail</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- HANYA TAMPILKAN MENU DASHBOARD JIKA PENGGUNA MEMILIKI PERAN 'owner' --}}
                {{-- MENU DASHBOARD --}}
@hasrole('owner')
<li class="nav-item has-treeview {{ Route::is('admin.inventory.dashboard') || Route::is('admin.inventory.cashflow') ? 'menu-open' : '' }}">
    <a href="#!" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p style="font-size: 15px;" class="font-weight-700">
            Dashboard
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item pl-2">
            <a href="{{ route('admin.inventory.dashboard') }}"
               class="nav-link {{ Route::is('admin.inventory.dashboard') ? 'active' : '' }}">
                <p>Dashboard Stok</p>
            </a>
        </li>
        <li class="nav-item pl-2">
            <a href="{{ route('admin.inventory.cashflow') }}"
               class="nav-link {{ Route::is('admin.inventory.cashflow') ? 'active' : '' }}">
                <p>Dashboard Keuangan</p>
            </a>
        </li>
    </ul>
</li>
@endhasrole


                @hasrole('owner')
                <li class="nav-item has-treeview {{ Route::is('admin.inventory.*') || Route::is('admin.cms.product_categories.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p style="font-size: 15px;" class="font-weight-700">
                            Pengelolaan Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.product_categories.index') }}" class="nav-link {{ Route::is('admin.cms.product_categories.*') ? 'active' : '' }}">
                                <p>Kategori Produk</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.inventory.products.index') }}" class="nav-link {{ Route::is('admin.inventory.products.*') ? 'active' : '' }}">
                                <p>Daftar Produk</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.inventory.history.index') }}" class="nav-link {{ Route::is('admin.inventory.history.*') ? 'active' : '' }}">
                                <p>Riwayat Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                @hasrole('owner')
                <li class="nav-item has-treeview {{ Route::is('admin.purchase-orders.*') || Route::is('admin.suppliers.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p style="font-size: 15px;" class="font-weight-700">
                            Pengelolaan Supplier
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Item menu baru untuk Daftar Supplier --}}
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ Route::is('admin.suppliers.index') ? 'active' : '' }}">
                                <p>Daftar Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.purchase-orders.index') }}" class="nav-link {{ Route::is('admin.purchase-orders.index') ? 'active' : '' }}">
                                <p>Pesanan Supplier</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole

               @hasrole('owner')
    <li class="nav-item has-treeview {{ Route::is('admin.cms.*') && !Route::is('admin.cms.customers.*') && !Route::is('admin.cms.customer-orders.*') && !Route::is('admin.cms.product_categories.*') ? 'menu-open' : '' }}">
        <a href="#!" class="nav-link">
            <i class="nav-icon fas fa-sitemap"></i>
            <p style="font-size: 15px;" class="font-weight-700">
                CMS
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            {{-- Tambahan: Menu untuk Hero Section --}}
            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.heroes.index') }}" class="nav-link {{ Route::is('admin.cms.heroes*') ? 'active' : '' }}">
                    <p>Banner Utama</p>
                </a>
            </li>
            {{-- Akhir Tambahan --}}

            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.banners.index') }}" class="nav-link {{ Route::is('admin.cms.banners*') ? 'active' : '' }}">
                    <p>Banner Promo</p>
                </a>
            </li>

            {{-- New CTA Menu Item --}}
            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.ctas.index') }}" class="nav-link {{ Route::is('admin.cms.ctas*') ? 'active' : '' }}">
                    <p>CTA Penutup</p>
                </a>
            </li>
            {{-- End of New CTA Menu Item --}}

            {{-- Tambahan: Menu untuk Social & E-commerce --}}
            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.socials.index') }}" class="nav-link {{ Route::is('admin.cms.socials*') ? 'active' : '' }}">
                    <p>Sosial & E-commerce</p>
                </a>
            </li>
            {{-- Akhir Tambahan --}}

            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.catalog.index') }}" class="nav-link {{ Route::is('admin.cms.catalog*') ? 'active' : '' }}">
                    <p>Manajemen Katalog</p>
                </a>
            </li>
            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.information-pages.index') }}" class="nav-link {{ Route::is('admin.cms.information-pages*') ? 'active' : '' }}">
                    <p>Informasi Perusahaan</p>
                </a>
            </li>

            <li class="nav-item pl-2">
                <a href="{{ route('admin.cms.testimonials.index') }}" class="nav-link {{ Route::is('admin.cms.testimonials.*') ? 'active' : '' }}">
                    <p>Testimoni Pelanggan</p>
                </a>
            </li>
        </ul>
    </li>
@endhasrole

                <li class="nav-item has-treeview {{ Route::is('admin.cms.customers.*') || Route::is('admin.cms.customer-orders.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p style="font-size: 15px;" class="font-weight-700">
                            Pesanan Pelanggan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.customers.index') }}" class="nav-link {{ Route::is('admin.cms.customers.*') ? 'active' : '' }}">
                                <p>Daftar Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.customer-orders.index') }}" class="nav-link {{ Route::is('admin.cms.customer-orders.*') ? 'active' : '' }}">
                                <p>Daftar Pesanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
