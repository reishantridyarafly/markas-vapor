@extends('layouts.frontend.main')
@section('title', 'Beranda')
@section('content')
    <!--====== App Content ======-->
    <div class="app-content">

        <!--====== Primary Slider ======-->
        <div class="s-skeleton s-skeleton--h-600 s-skeleton--bg-grey">
            <div class="owl-carousel primary-style-1" id="hero-slider">
                <div class="hero-slide hero-slide--1">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="slider-content slider-content--animation">

                                    <span class="content-span-1" style="color: #ffffff">Selamat Datang di Vape Store
                                        Kami!</span>

                                    <span class="content-span-2" style="color: #ffffff">Tempat Terbaik untuk Semua <br>
                                        Kebutuhan Vapor Anda</span>

                                    <span class="content-span-3" style="color: #ffffff">Kami menyediakan berbagai produk
                                        vapor
                                        berkualitas untuk pengalaman yang lebih menyenangkan.</span>

                                    <a class="shop-now-link btn--e-brand" href="{{ route('shop.index') }}">BELANJA
                                        SEKARANG</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Primary Slider ======-->


        <!--====== Section 2 ======-->
        <div class="u-s-p-y-60">
            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-16">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary u-s-m-b-12">TRENDING TERATAS</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Intro ======-->

            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="filter__grid-wrapper u-s-m-t-30">
                                <div class="row">
                                    @forelse ($most_purchased_products as $row)
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 u-s-m-b-30 filter__item">
                                            <div class="product-o product-o--hover-on product-o--radius">
                                                <div class="product-o__wrap">
                                                    <a class="aspect aspect--bg-grey aspect--square u-d-block product-view"
                                                        data-id="{{ $row->id }}"
                                                        href="{{ route('shop.detail', $row->slug) }}">
                                                        <img class="aspect__img"
                                                            src="{{ asset('storage/uploads/cover/' . $row->cover_photo) }}"
                                                            alt=""></a>
                                                    <div class="product-o__action-wrap">
                                                        <ul class="product-o__action-list">
                                                            <li>
                                                                <a href="javascript:(0);" id="addCart"
                                                                    class="product-view" data-id="{{ $row->id }}"
                                                                    title="Tambah Keranjang"><i
                                                                        class="fas fa-plus-circle"></i></a>
                                                                <input type="hidden" name="qty" id="qty"
                                                                    value="1">
                                                            </li>
                                                            <li>
                                                                <a href="javascript:(0);" id="addWishlist"
                                                                    data-id="{{ $row->id }}" title="Tambah Wishlist"><i
                                                                        class="fas fa-heart"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <span class="product-o__category product-view"
                                                    data-id="{{ $row->id }}">
                                                    <a
                                                        href="{{ route('shop.catalog', $row->catalog_slug) }}">{{ $row->catalog_name }}</a>
                                                </span>
                                                <span class="product-o__name product-view" data-id="{{ $row->id }}">
                                                    <a
                                                        href="{{ route('shop.detail', $row->slug) }}">{{ $row->name }}</a>
                                                </span>
                                                <div class="product-o__rating gl-rating-style">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $row->average_rating)
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i - $row->average_rating <= 0.5)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="product-o__review">({{ $row->ratings_count }})</span>
                                                </div>

                                                <span class="product-o__price">
                                                    {{ 'Rp ' . number_format($row->after_price, 0, ',', '.') }}

                                                    <span
                                                        class="product-o__discount">{{ $row->before_price ? 'Rp ' . number_format($row->before_price, 0, ',', '.') : '' }}</span></span>
                                            </div>
                                        </div>
                                    @empty
                                        <h4 class="text-center">Data tidak tersedia</h4>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="load-more">
                                <button onclick="window.location.href='{{ route('shop.index') }}'" class="btn btn--e-brand"
                                    type="button">Memuat lebih banyak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 2 ======-->

        <!--====== Section 4 ======-->
        <div class="u-s-p-b-60">

            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-46">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary u-s-m-b-12">PRODUK TERBARU</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Intro ======-->


            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="slider-fouc">
                        <div class="owl-carousel product-slider" data-item="4">
                            @forelse ($new_products as $new_product)
                                <div class="u-s-m-b-30">
                                    <div class="product-o product-o--hover-on">
                                        <div class="product-o__wrap">
                                            <a class="aspect aspect--bg-grey aspect--square u-d-block product-view"
                                                data-id="{{ $new_product->id }}"
                                                href="{{ route('shop.detail', $new_product->slug) }}">
                                                <img class="aspect__img"
                                                    src="{{ asset('storage/uploads/cover/' . $new_product->cover_photo) }}"
                                                    alt=""></a>
                                            <div class="product-o__action-wrap">
                                                <ul class="product-o__action-list">
                                                    <li>
                                                        <a href="javascript:(0);" id="addCart"
                                                            data-id="{{ $new_product->id }}" title="Tambah Keranjang"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                        <input type="hidden" name="qty" id="qty"
                                                            value="1">
                                                    </li>
                                                    <li>
                                                        <a href="javascript:(0);" id="addWishlist"
                                                            data-id="{{ $new_product->id }}" title="Tambah Wishlist"><i
                                                                class="fas fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <span class="product-o__category product-view" data-id="{{ $new_product->id }}">
                                            <a
                                                href="{{ route('shop.catalog', $new_product->catalog->slug) }}">{{ $new_product->catalog->name }}</a></span>
                                        <span class="product-o__name product-view" data-id="{{ $new_product->id }}">
                                            <a
                                                href="{{ route('shop.detail', $new_product->slug) }}">{{ $new_product->name }}</a></span>
                                        <div class="product-o__rating gl-rating-style">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $new_product->average_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - $new_product->average_rating <= 0.5)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="product-o__review">({{ $new_product->ratings_count }})</span>
                                        </div>

                                        <span class="product-o__price">
                                            {{ 'Rp ' . number_format($new_product->after_price, 0, ',', '.') }}

                                            <span
                                                class="product-o__discount">{{ $new_product->before_price ? 'Rp ' . number_format($new_product->before_price, 0, ',', '.') : '' }}</span></span>
                                    </div>
                                </div>
                            @empty
                                <h4 class="text-center">Data tidak tersedia</h4>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 4 ======-->


        <!--====== Section 9 ======-->
        <div class="u-s-p-b-60">
            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 u-s-m-b-30">
                            <div class="service u-h-100">
                                <div class="service__icon"><i class="fas fa-truck"></i></div>
                                <div class="service__info-wrap">

                                    <span class="service__info-text-1">Pengiriman Cepat dan Aman</span>

                                    <span class="service__info-text-2">Kami memastikan pengiriman cepat dan aman dengan
                                        nomor pelacakan untuk memantau status pesanan Anda.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 u-s-m-b-30">
                            <div class="service u-h-100">
                                <div class="service__icon"><i class="fas fa-thumbs-up"></i></div>
                                <div class="service__info-wrap">

                                    <span class="service__info-text-1">Garansi Resmi</span>

                                    <span class="service__info-text-2">Semua produk kami dilengkapi dengan garansi resmi
                                        dari pabrikan,
                                        melindungi Anda dari cacat produksi dan kerusakan.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 u-s-m-b-30">
                            <div class="service u-h-100">
                                <div class="service__icon"><i class="fas fa-headphones-alt"></i></div>
                                <div class="service__info-wrap">

                                    <span class="service__info-text-1">Layanan Pelanggan Responsif</span>

                                    <span class="service__info-text-2">Hubungi layanan pelanggan kami melalui Kontak.
                                        Kami siap membantu Anda setiap hari dari pukul 08.00 hingga 20.00.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 9 ======-->


        <!--====== Section 11 ======-->
        <div class="u-s-p-b-90 u-s-m-b-30">

            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-46">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary u-s-m-b-12">PENILAIAN PELANGGAN</h1>
                                <span class="section__span u-c-silver">APA YANG PELANGGAN KATAKAN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Intro ======-->


            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <!--====== Testimonial Slider ======-->
                    <div class="slider-fouc">
                        <div class="owl-carousel" id="testimonial-slider">
                            @foreach ($testimoni as $row)
                                <div class="testimonial">
                                    <div class="testimonial__img-wrap">
                                        <img class="testimonial__img"
                                            src="{{ asset('storage/users-avatar/' . $row->user->avatar) }}"
                                            alt="">
                                    </div>
                                    <div class="testimonial__content-wrap">

                                        <span class="testimonial__double-quote"><i class="fas fa-quote-right"></i></span>
                                        <blockquote class="testimonial__block-quote">
                                            <p>{{ $row->comment }}</p>
                                        </blockquote>

                                        <span
                                            class="testimonial__author">{{ $row->user->first_name . ' ' . $row->user->last_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!--====== End - Testimonial Slider ======-->
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 11 ======-->


        <!--====== Section 12 ======-->
        <div class="u-s-p-b-60">

            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">

                    <!--====== Brand Slider ======-->
                    <div class="slider-fouc">
                        <div class="owl-carousel" id="brand-slider" data-item="5">
                            @foreach ($catalog as $row)
                                <div class="brand-slide">
                                    <a href="{{ route('shop.catalog', $row->slug) }}">
                                        <img src="{{ asset('storage/catalog/' . $row->image) }}" alt=""></a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <!--====== End - Brand Slider ======-->
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 12 ======-->
    </div>
    <!--====== End - App Content ======-->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.product-view', function() {
                let productId = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('productViews.store') }}",
                    data: {
                        product_id: productId
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response.message);
                    },
                    error: function(xhr) {
                        console.error(xhr.status + ": " + xhr.responseText);
                    }
                });
            });

            $('body').on('click', '#addCart', function() {
                let id = $(this).data('id');
                let qty = $('#qty').val();
                $.ajax({
                    type: "POST",
                    url: "/keranjang/tambah/" + id,
                    data: {
                        id: id,
                        qty: qty,
                    },
                    dataType: 'json',
                    success: function(response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                        updateCartCount();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            $('body').on('click', '#addWishlist', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('wishlist.store') }}",
                    data: {
                        id: id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: response.icon,
                            title: response.message
                        });
                        updateCartCount();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });
        })
    </script>
@endsection
