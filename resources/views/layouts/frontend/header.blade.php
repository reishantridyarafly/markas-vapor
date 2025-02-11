<!--====== Main Header ======-->
<header class="header--style-1">

    <!--====== Nav 1 ======-->
    <nav class="primary-nav primary-nav-wrapper--border">
        <div class="container">

            <!--====== Primary Nav ======-->
            <div class="primary-nav">

                <!--====== Main Logo ======-->

                <a class="main-logo" href="{{ route('beranda.index') }}">

                    <img src="{{ asset('frontend/assets') }}/images/logo.png" alt=""></a>
                <!--====== End - Main Logo ======-->


                <!--====== Search Form ======-->
                <form class="main-form" action="{{ route('shop.search') }}" method="GET">
                    @csrf
                    <label for="main-search"></label>
                    <input class="input-text input-text--border-radius input-text--style-1" type="text"
                        id="main-search" name="search" placeholder="Search">

                    <button class="btn btn--icon fas fa-search main-search-button" type="submit"></button>
                </form>
                <!--====== End - Search Form ======-->


                <!--====== Dropdown Main plugin ======-->
                <div class="menu-init" id="navigation">

                    <button class="btn btn--icon toggle-button toggle-button--secondary fas fa-cogs"
                        type="button"></button>

                    <!--====== Menu ======-->
                    <div class="ah-lg-mode">

                        <span class="ah-close">✕ Close</span>

                        <!--====== List ======-->
                        <ul class="ah-list ah-list--design1 ah-list--link-color-secondary">

                            <li data-tooltip="tooltip" data-placement="left" title="Hubungi Admin">
                                <a href="{{ route('administrator.index') }}"><i class="fas fa-envelope"></i></a>
                            </li>
                            <li data-tooltip="tooltip" data-placement="left" title="Whatsapp">
                                <a href="https://wa.me/6285315922275" target="_blank"><i
                                        class="fas fa-phone-volume"></i></a>
                            </li>
                            <li class="has-dropdown" data-tooltip="tooltip" data-placement="left" title="Akun">

                                <a><i class="far fa-user-circle"></i></a>

                                <!--====== Dropdown ======-->

                                <span class="js-menu-toggle"></span>
                                <ul style="width:120px">
                                    @auth
                                        <li>
                                            <a href="{{ route('transaction.index') }}"><i
                                                    class="fas fa-user-circle u-s-m-r-6"></i>
                                                <span>Akun</span></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" id="logout-link"><i
                                                    class="fas fa-lock-open u-s-m-r-6"></i>
                                                <span>Keluar</span></a>
                                        </li>
                                    @endauth
                                    @guest
                                        <li>
                                            <a href="{{ route('login') }}"><i class="fas fa-lock u-s-m-r-6"></i>
                                                <span>Login</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ route('register') }}"><i class="fas fa-user-plus u-s-m-r-6"></i>
                                                <span>Register</span></a>
                                        </li>
                                    @endguest

                                </ul>
                                <!--====== End - Dropdown ======-->
                            </li>
                        </ul>
                        <!--====== End - List ======-->
                    </div>
                    <!--====== End - Menu ======-->
                </div>
                <!--====== End - Dropdown Main plugin ======-->
            </div>
            <!--====== End - Primary Nav ======-->
        </div>
    </nav>
    <!--====== End - Nav 1 ======-->


    <!--====== Nav 2 ======-->
    <nav class="secondary-nav-wrapper">
        <div class="container">

            <!--====== Secondary Nav ======-->
            <div class="secondary-nav">

                <!--====== Dropdown Main plugin ======-->
                <div class="menu-init" id="navigation1">
                    <span class="mega-text">M</span>
                </div>
                <!--====== End - Dropdown Main plugin ======-->


                <!--====== Dropdown Main plugin ======-->
                <div class="menu-init" id="navigation2">

                    <button class="btn btn--icon toggle-button toggle-button--secondary fas fa-cog"
                        type="button"></button>

                    <!--====== Menu ======-->
                    <div class="ah-lg-mode">

                        <span class="ah-close">✕ Close</span>

                        <!--====== List ======-->
                        <ul class="ah-list ah-list--design2 ah-list--link-color-secondary">
                            <li>
                                <a href="{{ route('beranda.index') }}">Beranda</a>
                            </li>
                            <li>
                                <a href="{{ route('about.index') }}">Tentang</a>
                            </li>
                            <li>
                                <a href="{{ route('shop.index') }}">Belanja</a>
                            </li>
                            <li>
                                <a href="{{ route('faq.index') }}">FAQ</a>
                            </li>
                            <li>
                                <a href="{{ route('contact-message.index') }}">Kontak</a>
                            </li>
                        </ul>
                        <!--====== End - List ======-->
                    </div>
                    <!--====== End - Menu ======-->
                </div>
                <!--====== End - Dropdown Main plugin ======-->


                <!--====== Dropdown Main plugin ======-->
                <div class="menu-init" id="navigation3">

                    <button
                        class="btn btn--icon toggle-button toggle-button--secondary fas fa-shopping-bag toggle-button-shop"
                        type="button"></button>

                    <span class="total-item-round">0</span>

                    <!--====== Menu ======-->
                    <div class="ah-lg-mode">

                        <span class="ah-close">✕ Tutup</span>

                        <!--====== List ======-->
                        <ul class="ah-list ah-list--design1 ah-list--link-color-secondary">
                            <li>
                                <a href="{{ route('beranda.index') }}"><i class="fas fa-home u-c-brand"></i></a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}"><i class="far fa-heart"></i></a>
                            </li>
                            <li>
                                <a href="@auth{{ route('cart.index', auth()->user()->id) }} @endauth"
                                    class="mini-cart-shop-link"><i class="fas fa-shopping-bag"></i>
                                    <span class="total-item-round" id="cart-count">0</span></a>
                            </li>
                        </ul>
                        <!--====== End - List ======-->
                    </div>
                    <!--====== End - Menu ======-->
                </div>
                <!--====== End - Dropdown Main plugin ======-->
            </div>
            <!--====== End - Secondary Nav ======-->
        </div>
    </nav>
    <!--====== End - Nav 2 ======-->
</header>
<!--====== End - Main Header ======-->

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
