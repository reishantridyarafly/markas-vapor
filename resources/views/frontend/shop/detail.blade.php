@extends('layouts.frontend.main')
@section('title', 'Detail Produk')
@section('content')
    <!--====== App Content ======-->
    <div class="app-content">

        <!--====== Section 1 ======-->
        <div class="u-s-p-t-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">

                        <!--====== Product Breadcrumb ======-->
                        <div class="pd-breadcrumb u-s-m-b-30">
                            <ul class="pd-breadcrumb__list">
                                <li class="has-separator">
                                    <a href="{{ route('beranda.index') }}">Beranda</a>
                                </li>
                                <li class="has-separator">
                                    <a href="{{ route('shop.index') }}">Belanja</a>
                                </li>
                                <li class="is-marked">
                                    <a href="{{ route('shop.detail', $product->slug) }}">{{ $product->name }}</a>
                                </li>
                            </ul>
                        </div>
                        <!--====== End - Product Breadcrumb ======-->


                        <!--====== Product Detail Zoom ======-->
                        <div class="pd u-s-m-b-30">
                            <div class="slider-fouc pd-wrap">
                                <div id="pd-o-initiate">
                                    @foreach ($product->photos as $photo)
                                        <div class="pd-o-img-wrap"
                                            data-src="{{ asset('storage/uploads/products/' . $photo->photo_name) }}">
                                            <img class="u-img-fluid"
                                                src="{{ asset('storage/uploads/products/' . $photo->photo_name) }}"
                                                data-zoom-image="{{ asset('storage/uploads/products/' . $photo->photo_name) }}"
                                                alt="">
                                        </div>
                                    @endforeach
                                </div>

                                <span class="pd-text">Click for larger zoom</span>
                            </div>
                            <div class="u-s-m-t-15">
                                <div class="slider-fouc">
                                    <div id="pd-o-thumbnail">
                                        @foreach ($product->photos as $photo)
                                            <div>
                                                <img class="u-img-fluid"
                                                    src="{{ asset('storage/uploads/products/' . $photo->photo_name) }}"
                                                    alt="">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--====== End - Product Detail Zoom ======-->
                    </div>
                    <div class="col-lg-7">

                        <!--====== Product Right Side Details ======-->
                        <div class="pd-detail">
                            <div>

                                <span class="pd-detail__name">{{ $product->name }}</span>
                            </div>
                            <div>
                                <div class="pd-detail__inline">

                                    <span
                                        class="pd-detail__price">{{ 'Rp ' . number_format($product->after_price, 0, ',', '.') }}</span>

                                    <span class="pd-detail__discount"></span><del
                                        class="pd-detail__del">{{ $product->before_price ? 'Rp ' . number_format($product->before_price, 0, ',', '.') : '' }}</del>
                                </div>
                            </div>
                            <div class="u-s-m-b-15">
                                <div class="pd-detail__rating gl-rating-style">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $product->average_rating)
                                            <i class="fas fa-star"></i>
                                        @elseif ($i - $product->average_rating <= 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="pd-detail__review u-s-m-l-4">

                                        <a data-click-scroll="#view-review">{{ $product->ratings_count }}
                                            Ulasan</a></span>
                                </div>

                            </div>
                            <div class="u-s-m-b-15">
                                <div class="pd-detail__inline">

                                    <span class="pd-detail__stock">{{ $product->stock }} Stok</span>
                                </div>
                            </div>
                            <div class="u-s-m-b-15">

                                <span class="pd-detail__preview-desc">{{ $product->short_description }}</span>
                            </div>
                            <div class="u-s-m-b-15">
                                <form class="pd-detail__form" method="POST"
                                    action="{{ route('checkout.directCheckout') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                                    <input type="hidden" name="weight" id="weight" value="{{ $product->weight }}">
                                    <div class="pd-detail-inline-2">
                                        <div class="u-s-m-b-15">

                                            <!--====== Input Counter ======-->
                                            <div class="input-counter">

                                                <span class="input-counter__minus fas fa-minus"></span>

                                                <input class="input-counter__text input-counter--text-primary-style qty_display"
                                                    id="qty" name="qty" value="1" type="text"
                                                    data-min="1" data-max="1000">

                                                <span class="input-counter__plus fas fa-plus"></span>
                                            </div>
                                            <!--====== End - Input Counter ======-->
                                        </div>
                                        <div class="u-s-m-b-15">
                                            <button class="btn btn--e-brand-b-2" id="addCart" type="button"
                                                data-id="{{ $product->id }}">Tambah Keranjang</button>
                                            <button class="btn btn--e-brand-b-2" type="submit">Belanja
                                                Sekarang</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--====== End - Product Right Side Details ======-->
                    </div>
                </div>
            </div>
        </div>

        <!--====== Product Detail Tab ======-->
        <div class="u-s-p-y-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pd-tab">
                            <div class="u-s-m-b-30">
                                <ul class="nav pd-tab__list">
                                    <li class="nav-item">

                                        <a class="nav-link active" data-toggle="tab" href="#pd-desc">DESKRIPSI</a>
                                    </li>
                                    <li class="nav-item">

                                        <a class="nav-link" id="view-review" data-toggle="tab" href="#pd-rev">ULASAN

                                            <span>({{ $product->ratings_count }})</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">

                                <!--====== Tab 1 ======-->
                                <div class="tab-pane fade show active" id="pd-desc">
                                    <div class="pd-tab__desc">
                                        <div class="u-s-m-b-15">
                                            <p>{!! $product->description !!}</p>
                                        </div>

                                        <div class="u-s-m-b-15">
                                            <h4>INFORMASI PRODUK</h4>
                                        </div>
                                        <div class="u-s-m-b-15">
                                            <div class="pd-table gl-scroll">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>Stok</td>
                                                            <td>{{ $product->stock }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Katalog</td>
                                                            <td>{{ $product->catalog->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Berat</td>
                                                            <td>{{ $product->weight }} Gram</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--====== End - Tab 1 ======-->

                                <!--====== Tab 2 ======-->
                                <div class="tab-pane" id="pd-rev">
                                    <div class="pd-tab__rev">
                                        <div class="u-s-m-b-30">
                                            <div class="pd-tab__rev-score">
                                                <div class="u-s-m-b-8">
                                                    <h2>{{ $product->ratings_count }} Ulasan -
                                                        {{ $product->average_rating }} (Keseluruhan)
                                                    </h2>
                                                </div>
                                                <div class="gl-rating-style-2 u-s-m-b-8">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $product->average_rating)
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i - $product->average_rating <= 0.5)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <h4>Kami ingin mendengar pendapat Anda!</h4>
                                                </div>

                                                <span class="gl-text">Beri tahu kami pendapat Anda tentang item ini</span>
                                            </div>
                                        </div>
                                        <div class="u-s-m-b-30">
                                            <form class="pd-tab__rev-f1">
                                                <div class="rev-f1__review">
                                                    @foreach ($rating_reviews as $row)
                                                        <div class="review-o u-s-m-b-15">
                                                            <div class="review-o__info u-s-m-b-8">

                                                                <span
                                                                    class="review-o__name">{{ $row->user->first_name . ' ' . $row->user->last_name }}</span>

                                                                <span
                                                                    class="review-o__date">{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y H:i:s') }}</span>
                                                            </div>
                                                            <div class="review-o__rating gl-rating-style u-s-m-b-8">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $row->rating)
                                                                        <i class="fas fa-star"></i>
                                                                    @elseif ($i - $row->rating <= 0.5)
                                                                        <i class="fas fa-star-half-alt"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                                <span>({{ $row->rating }})</span>
                                                            </div>
                                                            <p class="review-o__text">{{ $row->comment }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                        @if ($hasPurchased)
                                            @if (!$hasRated)
                                                <div class="u-s-m-b-30">
                                                    <form class="pd-tab__rev-f2" id="form_review">
                                                        <h2 class="u-s-m-b-15">Tambah Penilaian</h2>
                                                        <div class="u-s-m-b-30">
                                                            <div class="rev-f2__table-wrap gl-scroll">
                                                                <table class="rev-f2__table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><span>(1)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star-half-alt"></i><span>(1.5)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><span>(2)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star-half-alt"></i><span>(2.5)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><span>(3)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star-half-alt"></i><span>(3.5)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><span>(4)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star-half-alt"></i><span>(4.5)</span>
                                                                                </div>
                                                                            </th>
                                                                            <th>
                                                                                <div class="gl-rating-style-2"><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><i
                                                                                        class="fas fa-star"></i><span>(5)</span>
                                                                                </div>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-1"
                                                                                        name="rating" value="1">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-1"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-1.5"
                                                                                        name="rating" value="1.5">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-1.5"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-2"
                                                                                        name="rating" value="2">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-2"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-2.5"
                                                                                        name="rating" value="2.5">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-2.5"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-3"
                                                                                        name="rating" value="3">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-3"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-3.5"
                                                                                        name="rating" value="3.5">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-3.5"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-4"
                                                                                        name="rating" value="4">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-4"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-4.5"
                                                                                        name="rating" value="4.5">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-4.5"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="radio-box">
                                                                                    <input type="radio" id="star-5"
                                                                                        name="rating" value="5">
                                                                                    <div
                                                                                        class="radio-box__state radio-box__state--primary">
                                                                                        <label class="radio-box__label"
                                                                                            for="star-5"></label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="rev-f2__group">
                                                            <div class="u-s-m-b-15">
                                                                <label class="gl-label" for="reviewer-text">Penilaian Kamu
                                                                    *</label>
                                                                <textarea class="text-area text-area--primary-style" id="comment" name="comment" required></textarea>
                                                            </div>
                                                            <div>
                                                                <p class="u-s-m-b-30">

                                                                    <label class="gl-label" for="reviewer-name">NAMA
                                                                        LENGKAP
                                                                        *</label>
                                                                    <input class="input-text input-text--primary-style"
                                                                        value="{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}"
                                                                        type="text" id="name" name="name">
                                                                    <input type="hidden" name="product_id"
                                                                        id="product_id" value="{{ $product->id }}">
                                                                </p>
                                                                <p class="u-s-m-b-30">

                                                                    <label class="gl-label" for="reviewer-email">EMAIL
                                                                        *</label>
                                                                    <input class="input-text input-text--primary-style"
                                                                        type="text" id="email" name="email"
                                                                        value="{{ auth()->user()->email }}">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <button class="btn btn--e-brand-shadow" type="submit"
                                                                id="send_review">KIRIM</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <!--====== End - Tab 3 ======-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Product Detail Tab ======-->
        <div class="u-s-p-b-90">

            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-46">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary u-s-m-b-12">REKOMENDASI PRODUK</h1>
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
                            @foreach ($recommendedProducts as $recommendedProduct)
                                <div class="u-s-m-b-30">
                                    <div class="product-o product-o--hover-on">
                                        <div class="product-o__wrap">

                                            <a class="aspect aspect--bg-grey aspect--square u-d-block product-view"
                                                data-id="{{ $recommendedProduct->id }}"
                                                href="{{ route('shop.detail', $recommendedProduct->slug) }}">

                                                <img class="aspect__img"
                                                    src="{{ asset('storage/uploads/cover/' . $recommendedProduct->cover_photo) }}"
                                                    alt=""></a>
                                            <div class="product-o__action-wrap">
                                                <ul class="product-o__action-list">
                                                    <li>
                                                        <a href="javascript:(0);" id="addCart" class="product-view"
                                                            data-id="{{ $recommendedProduct->id }}"
                                                            title="Tambah Keranjang"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                        <input type="hidden" name="qty" id="qty"
                                                            value="1">
                                                    </li>
                                                    <li>
                                                        <a href="javascript:(0);" id="addWishlist"
                                                            data-id="{{ $recommendedProduct->id }}"
                                                            title="Tambah Wishlist"><i class="fas fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <span class="product-o__category product-view"
                                            data-id="{{ $recommendedProduct->id }}">

                                            <a
                                                href="shop-side-version-2.html">{{ $recommendedProduct->catalog->name }}</a></span>

                                        <span class="product-o__name product-view"
                                            data-id="{{ $recommendedProduct->id }}">

                                            <a
                                                href="{{ route('shop.detail', $recommendedProduct->slug) }}">{{ $recommendedProduct->name }}</a></span>
                                        <div class="product-o__rating gl-rating-style">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $recommendedProduct->average_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - $recommendedProduct->average_rating <= 0.5)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor

                                            <span
                                                class="product-o__review">({{ $recommendedProduct->ratings_count }})</span>
                                        </div>

                                        <span class="product-o__price">
                                            {{ 'Rp ' . number_format($recommendedProduct->after_price, 0, ',', '.') }}

                                            <span
                                                class="product-o__discount">{{ $recommendedProduct->before_price ? 'Rp ' . number_format($recommendedProduct->before_price, 0, ',', '.') : '' }}</span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 1 ======-->

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

            const maxStock = {{ $product->stock }};
            $('.qty_display').attr('data-max', maxStock);

            $('.qty_display').on('change', function() {
                let qty = parseInt($(this).val());

                if (qty > maxStock) {
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
                        icon: "warning",
                        title: `Stok tersedia hanya ${maxStock} item`
                    });
                    $(this).val(maxStock);
                }

                if (qty < 1 || isNaN(qty)) {
                    $(this).val(1);
                }
            });

            $('.input-counter__plus').on('click', function() {
                let qty = parseInt($('.qty_display').val());

                if (qty >= maxStock) {
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
                        icon: "warning",
                        title: `Stok tersedia hanya ${maxStock} item`
                    });
                    return false;
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
                let qty = $('.qty_display').val();


                if (qty > maxStock) {
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
                        icon: "warning",
                        title: `Stok tersedia hanya ${maxStock} item`
                    });
                    $(this).val(maxStock);
                }

                if (qty < 1 || isNaN(qty)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jumlah Tidak Valid',
                        text: 'Jumlah minimal adalah 1 item',
                        confirmButtonColor: '#3085d6',
                    });
                    $('.qty_display').val(1);
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "/keranjang/tambah/" + id,
                    data: {
                        id: id,
                        qty: qty,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#addCart').attr('disable', 'disabled');
                        $('#addCart').text('Proses...');
                    },
                    complete: function() {
                        $('#addCart').removeAttr('disable');
                        $('#addCart').text('Tambah Keranjang');
                    },
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

            $('.pd-detail__form').on('submit', function(e) {
                let qty = parseInt($('.qty_display').val());


                if (qty > maxStock) {
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
                        icon: "warning",
                        title: `Stok tersedia hanya ${maxStock} item`
                    });
                    $(this).val(maxStock);
                }

                if (qty < 1 || isNaN(qty)) {
                    e.preventDefault();
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
                        icon: "error",
                        title: "Jumlah Tidak Valid",
                        text: "Jumlah minimal adalah 1 item"
                    });
                    $('.qty_display').val(1);
                    return false;
                }

                if (maxStock === 0) {
                    e.preventDefault();
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
                        icon: "warning",
                        title: "Stok Habis",
                        text: "Maaf, produk ini sedang tidak tersedia"
                    });
                    return false;
                }
            });

            $('#form_review').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('ratings.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#send_review').attr('disable', 'disabled');
                        $('#send_review').text('PROSES...');
                    },
                    complete: function() {
                        $('#send_review').removeAttr('disable');
                        $('#send_review').text('KIRIM');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                        }).then(function() {
                            top.location.href =
                                "{{ route('shop.detail', ['slug' => $product->slug]) }}";
                        });
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
        });
    </script>
@endsection
