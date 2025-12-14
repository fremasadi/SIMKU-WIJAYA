<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <i class="bx bxs-store text-primary" style="font-size: 30px;"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">simku-wijaya</span>
        </a>

        <a href="javascript:void(0);" 
           class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ url('/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- TRANSAKSI -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>

        <!-- Pemasukan -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-down-arrow-circle"></i>
                <div>Pemasukan</div>
            </a>
        </li>

        <!-- Pengeluaran -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-up-arrow-circle"></i>
                <div>Pengeluaran</div>
            </a>
        </li>

        <!-- Tambahan Presensi & Gaji -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div>Presensi Karyawan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div>Gaji Karyawan</div>
            </a>
        </li>

        <!-- MASTER DATA -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Pembelian</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>Penjualan</div>
            </a>
        </li>

        @if(auth()->user()->role == 'super_admin')
        <!-- ADMIN -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Admin</span>
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
