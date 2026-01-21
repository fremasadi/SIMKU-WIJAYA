<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <i class="bx bxs-store text-primary" style="font-size: 30px;"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">simku-wijaya</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        @if (auth()->user()->role != 'kasir')
            <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboard</div>
                </a>
            </li>
        @endif

        <!-- TRANSAKSI -->
        @if (auth()->user()->role != 'kasir')
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li> --}}

            <!-- Pembelian -->
            {{-- <li class="menu-item {{ request()->is('pembelian*') ? 'active' : '' }}">
                <a href="{{ route('pembelian.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div>Pembelian</div>
                </a>
            </li> --}}

        @endif

        <!-- Penjualan (Tampilkan untuk semua) -->
        {{-- <li class="menu-item {{ request()->is('penjualan*') ? 'active' : '' }}">
            <a href="{{ route('penjualan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>Penjualan</div>
            </a>
        </li> --}}

        <!-- Gaji Karyawan -->
        {{-- @if (auth()->user()->role != 'kasir')
            <li class="menu-item {{ request()->is('gaji*') ? 'active' : '' }}">
                <a href="{{ route('gaji.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div>Gaji Karyawan</div>
                </a>
            </li>
        @endif --}}

        <!-- MASTER DATA -->
        @if (auth()->user()->role != 'kasir')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>

            <!-- Bahan Baku -->
            <li class="menu-item {{ request()->is('bahan-baku*') ? 'active' : '' }}">
                <a href="{{ route('bahan-baku.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>Bahan Baku</div>
                </a>
            </li>

            <!-- Produk -->
            <li class="menu-item {{ Route::currentRouteName() == 'produk.index' ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>Produk</div>
                </a>
            </li>


            <!-- Produksi -->
            <li class="menu-item {{ Route::currentRouteName() == 'produksi.index' ? 'active' : '' }}">
                <a href="{{ route('produksi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>Produksi</div>
                </a>
            </li>

            <!-- Karyawan -->
            <li class="menu-item {{ request()->is('karyawan*') ? 'active' : '' }}">
                <a href="{{ route('karyawan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div>Karyawan</div>
                </a>
            </li>

            <!-- Presensi -->
            {{-- <li class="menu-item {{ request()->is('presensi*') ? 'active' : '' }}">
        <a href="{{ route('presensi.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
            <div>Presensi Karyawan</div>
        </a>
    </li>
   --}}
        @endif
        <!-- Owner -->
        @if (auth()->user()->role == 'owner')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Owner</span>
            </li>

            <li class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Manajemen User</div>
                </a>
            </li>
        @endif

    </ul>

</aside>
