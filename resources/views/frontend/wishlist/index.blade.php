@extends('layouts.frontend.main')
@section('title', 'Wishlist')
@section('content')
    <!--====== App Content ======-->
    <div class="app-content">

        <!--====== Section 1 ======-->
        <div class="u-s-p-y-60">

            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="breadcrumb">
                        <div class="breadcrumb__wrap">
                            <ul class="breadcrumb__list">
                                <li class="has-separator">
                                    <a href="{{ route('beranda.index') }}">Beranda</a>
                                </li>
                                <li class="is-marked">
                                    <a href="javascript:(0);">Wishlist</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Section 1 ======-->

        <!--====== Section 2 ======-->
        <div class="u-s-p-b-60">

            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-60">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary">Wishlist</h1>
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
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            @forelse ($wishlist as $row)
                                <!--====== Wishlist Product ======-->
                                <div class="w-r u-s-m-b-30">
                                    <div class="w-r__container">
                                        <div class="w-r__wrap-1">
                                            <div class="w-r__img-wrap">

                                                <img class="u-img-fluid"
                                                    src="{{ asset('storage/uploads/cover/' . $row->product->cover_photo) }}"
                                                    alt="">
                                            </div>
                                            <div class="w-r__info">

                                                <span class="w-r__name">

                                                    <a
                                                        href="{{ route('shop.detail', $row->product->slug) }}">{{ $row->product->name }}</a></span>

                                                <span class="w-r__category">

                                                    <a
                                                        href="shop-side-version-2.html">{{ $row->product->catalog->name }}</a></span>

                                                <span class="w-r__price">
                                                    {{ 'Rp ' . number_format($row->product->after_price, 0, ',', '.') }}

                                                    <span
                                                        class="w-r__discount">{{ $row->product->before_price ? 'Rp ' . number_format($row->product->before_price, 0, ',', '.') : '' }}</span></span>
                                            </div>
                                        </div>
                                        <div class="w-r__wrap-2">

                                            <a class="w-r__link btn--e-brand-b-2" href="javascript:(0);" id="addCart"
                                                data-id="{{ $row->product->id }}">TAMBAH KERANJANG</a>
                                            <input type="hidden" name="qty" id="qty" value="1">

                                            <a class="w-r__link btn--e-transparent-platinum-b-2"
                                                href="{{ route('shop.detail', $row->product->slug) }}">LIHAT</a>

                                            <a class="w-r__link btn--e-transparent-platinum-b-2" href="javascript:(0);"
                                                id="remove" data-id="{{ $row->id }}">HAPUS</a>
                                        </div>
                                    </div>
                                </div>
                                <!--====== End - Wishlist Product ======-->
                            @empty
                                <div class="col-lg-12">
                                    <div class="section__text-wrap">
                                        <h4 class="u-c-secondary">Data tidak tersedia</h4>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 2 ======-->
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

            $('body').on('click', '#remove', function() {
                let id = $(this).data('id');
                let row = $(this).closest('.w-r');
                $.ajax({
                    type: "DELETE",
                    url: "/wishlist/" + id,
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
                        if (response.icon == 'success') {
                            row.remove();
                        }
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
