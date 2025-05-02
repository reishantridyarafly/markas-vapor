<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('beranda.index') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ asset('backend/assets') }}/images/logo.png" alt="" class="logo logo-lg"
                    style="width: 50px;">
                <img src="{{ asset('backend/assets') }}/images/logo.png" alt="" class="logo logo-sm">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                @if (auth()->user()->type != 'Pelanggan')
                    <li class="nxl-item {{ request()->routeIs(['dashboard.*']) ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->type == 'Pelanggan')
                    <li class="nxl-item {{ request()->routeIs(['transaction.*']) ? 'active' : '' }}">
                        <a href="{{ route('transaction.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>
                            <span class="nxl-mtext">Transaksi</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->type == 'Administrator')
                    <li
                        class="nxl-item nxl-hasmenu {{ request()->routeIs(['transaction.*']) ? 'active nxl-trigger' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>
                            <span class="nxl-mtext">Transaksi</span><span class="nxl-arrow"><i
                                    class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs(['transaction.index']) ? 'active' : '' }}"><a
                                    class="nxl-link" href="{{ route('transaction.index') }}">Transaksi</a></li>

                            <li class="nxl-item {{ request()->routeIs(['transaction.create']) ? 'active' : '' }}"><a
                                    class="nxl-link" href="{{ route('transaction.create') }}">Tambah Transaksi</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['catalog.*']) ? 'active' : '' }}">
                        <a href="{{ route('catalog.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-archive"></i></span>
                            <span class="nxl-mtext">Katalog</span>
                        </a>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['product.*']) ? 'active' : '' }}">
                        <a href="{{ route('product.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-package"></i></span>
                            <span class="nxl-mtext">Produk</span>
                        </a>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['account.*']) ? 'active' : '' }}">
                        <a href="{{ route('account.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-credit-card"></i></span>
                            <span class="nxl-mtext">Rekening Bank</span>
                        </a>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['contact.*']) ? 'active' : '' }}">
                        <a href="{{ route('contact.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-book"></i></span>
                            <span class="nxl-mtext">Pesan Kontak</span>
                        </a>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['rating.*']) ? 'active' : '' }}">
                        <a href="{{ route('rating.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-star"></i></span>
                            <span class="nxl-mtext">Penilaian</span>
                        </a>
                    </li>

                    <li class="nxl-item {{ request()->routeIs(['customers.*']) ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-users"></i></span>
                            <span class="nxl-mtext">Pelanggan</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->type == 'Pemilik')
                    <li class="nxl-item {{ request()->routeIs(['users.*']) ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-user"></i></span>
                            <span class="nxl-mtext">Administrator</span>
                        </a>
                    </li>

                    <li
                        class="nxl-item nxl-hasmenu {{ request()->routeIs(['daily-report.*', 'monthly-report.*', 'yearly-report.*']) ? 'active nxl-trigger' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-file-text"></i></span>
                            <span class="nxl-mtext">Laporan</span><span class="nxl-arrow"><i
                                    class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs(['daily-report.index']) ? 'active' : '' }}"><a
                                    class="nxl-link" href="{{ route('daily-report.index') }}">Harian</a></li>
                            <li class="nxl-item {{ request()->routeIs(['monthly-report.index']) ? 'active' : '' }}"><a
                                    class="nxl-link" href="{{ route('monthly-report.index') }}">Bulanan</a></li>
                            <li class="nxl-item {{ request()->routeIs(['yearly-report.index']) ? 'active' : '' }}"><a
                                    class="nxl-link" href="{{ route('yearly-report.index') }}">Tahunan</a></li>
                        </ul>
                    </li>
                @endif

                <li class="nxl-item {{ request()->routeIs(['administrator.*']) ? 'active' : '' }}">
                    <a href="{{ route('administrator.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">Administrator</span>
                    </a>
                </li>

                @if (auth()->user()->type != 'Pemilik')
                    <li class="nxl-item {{ request()->routeIs(['refund.*']) ? 'active' : '' }}">
                        <a href="{{ route('refund.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-refresh-ccw"></i></span>
                            <span class="nxl-mtext">Pengembalian</span>
                        </a>
                    </li>
                @endif
                <li class="nxl-item {{ request()->routeIs(['profile.*']) ? 'active' : '' }}">
                    <a href="{{ route('profile.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Profile</span>
                    </a>
                </li>

                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cpu"></i></span>
                        <span class="nxl-mtext">FP Growth</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link"
                                href="{{ route('account.recommendations') }}">Rekomendasi Produk</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.analytics') }}">Analisis FP
                                Growth</a></li>
                    </ul>
                </li>

                <li class="nxl-item {{ request()->routeIs(['address.*']) ? 'active' : '' }}">
                    <a href="{{ route('address.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-map-pin"></i></span>
                        <span class="nxl-mtext">Alamat</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</nav>
