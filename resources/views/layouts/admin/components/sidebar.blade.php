<aside class="main-sidebar sidebar-dark-secondary elevation-4" style="background-color: #9B4141;">
    <a href="{{ url('/') }}" class="brand-link d-flex justify-content-center">
        <span class="brand-text font-weight-light text-white">Ahlinya Retail</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @hasrole('owner')
                <li class="nav-item has-treeview {{ Route::is('admin.inventory.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <p style="font-size: 15px;" class="font-weight-700">
                            <i class="nav-icon fas fa-database"></i>
                            Pengelolaan Stok
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.inventory.dashboard') }}"class="nav-link {{ Route::is('admin.inventory.dashboard') ? 'active' : '' }}">
                                <p>Dashboard Stok</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.inventory.products.index') }}"class="nav-link {{ Route::is('admin.inventory.products.*') ? 'active' : '' }}">
                                <p>Daftar Stok Produk</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.inventory.history.index') }}" class="nav-link {{ Route::is('admin.inventory.history.*') ? 'active' : '' }}">
                                <p>Riwayat Mutasi Stok</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                @hasrole('owner')
                <li class="nav-item has-treeview {{ Route::is('admin.purchase-orders.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <p style="font-size: 15px;" class="font-weight-700">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            Pemesanan Supplier
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.purchase-orders.index') }}" class="nav-link {{ Route::is('admin.purchase-orders.index') ? 'active' : '' }}">
                                <p>Riwayat Pemesanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                @hasrole('owner')
                <li class="nav-item has-treeview {{ Route::is('admin.cms.*') && !Route::is('admin.cms.customers.*') && !Route::is('admin.cms.customer-orders.*') ? 'menu-open' : '' }}">
                    <a href="#!" class="nav-link">
                        <p style="font-size: 15px;" class="font-weight-700">
                            <i class="nav-icon fas fa-paint-brush"></i>
                            CMS
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.product_categories.index') }}" class="nav-link {{ Route::is('admin.cms.product_categories.*') ? 'active' : '' }}">
                                <p>Manajemen Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.catalog.index') }}" class="nav-link {{ Route::is('admin.cms.catalog*') ? 'active' : '' }}">
                                <p>Manajemen Produk</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.banners.index') }}" class="nav-link {{ Route::is('admin.cms.banners*') ? 'active' : '' }}">
                                <p>Manajemen Banner</p>
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
                        <p style="font-size: 15px;" class="font-weight-700">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            Pesanan Pelanggan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.customers.index') }}" class="nav-link {{ Route::is('admin.cms.customers.*') ? 'active' : '' }}">
                                <p>Data Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item pl-2">
                            <a href="{{ route('admin.cms.customer-orders.index') }}" class="nav-link {{ Route::is('admin.cms.customer-orders.*') ? 'active' : '' }}">
                                <p>Order</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
