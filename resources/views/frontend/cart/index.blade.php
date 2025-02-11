@extends('layouts.frontend.main')
@section('title', 'Keranjang')
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
                                    <a href="javascript:(0);">Keranjang</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Section 1 ======-->

        <form class="f-cart" action="{{ route('checkout.cartCheckout') }}" method="POST">
            @csrf
            <!--====== Section 2 ======-->
            <div class="u-s-p-b-60">

                <!--====== Section Intro ======-->
                <div class="section__intro u-s-m-b-60">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section__text-wrap">
                                    <h1 class="section__heading u-c-secondary">KERANJANG BELANJA</h1>
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
                            <div class="col-lg-12 col-md-12 col-sm-12 u-s-m-b-30">
                                <div class="table-responsive">
                                    <table class="table-p">
                                        <tbody>
                                            @forelse ($items as $item)
                                                <!--====== Row ======-->
                                                <tr>
                                                    <td>
                                                        <div class="check-box">
                                                            <input type="checkbox" name="selected_items[]"
                                                                value="{{ $item->id }}">
                                                            <div class="check-box__state check-box__state--primary">
                                                                <label class="check-box__label"
                                                                    for="term-and-condition"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="table-p__box">
                                                            <div class="table-p__img-wrap">
                                                                <img class="u-img-fluid"
                                                                    src="{{ asset('storage/uploads/cover/' . $item->product->cover_photo) }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="table-p__info">
                                                                <span class="table-p__name">
                                                                    <a
                                                                        href="{{ route('shop.detail', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                                </span>
                                                                <span class="table-p__category">
                                                                    <a
                                                                        href="{{ route('shop.catalog', $item->product->catalog->slug) }}">{{ $item->product->catalog->name }}</a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="table-p__price">{{ 'Rp ' . number_format($item->product->after_price, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="table-p__input-counter-wrap">

                                                            <!--====== Input Counter ======-->
                                                            <div class="input-counter">

                                                                <span class="input-counter__minus fas fa-minus"></span>

                                                                <input
                                                                    class="input-counter__text input-counter--text-primary-style"
                                                                    type="text" id="qty" name="qty"
                                                                    value="{{ $item->quantity }}" data-min="1"
                                                                    data-max="1000" data-id="{{ $item->id }}"
                                                                    data-price="{{ $item->product->after_price }}">

                                                                <span class="input-counter__plus fas fa-plus"></span>
                                                            </div>
                                                            <!--====== End - Input Counter ======-->
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="table-p__del-wrap">
                                                            <a class="far fa-trash-alt table-p__delete-link remove"
                                                                href="javascript:(0);" data-id="{{ $item->id }}"></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--====== End - Row ======-->
                                            @empty
                                                <!--====== Row ======-->
                                                <tr>
                                                    <td colspan="5" class="text-center">Data tidak tersedia</td>
                                                </tr>
                                                <!--====== End - Row ======-->
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--====== End - Section Content ======-->
            </div>
            <!--====== End - Section 2 ======-->


            <!--====== Section 3 ======-->
            <div class="u-s-p-b-60">
                <div class="section__content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 u-s-m-b-30">
                                <div class="row justify-content-end">
                                    <div class="col-lg-8 col-md-8"></div>
                                    <div class="col-lg-4 col-md-4 u-s-m-b-30">
                                        <div class="f-cart__pad-box">
                                            <div class="u-s-m-b-30">
                                                <table class="f-cart__table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td id="cart-total">Rp 0</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div>
                                                <button class="btn btn--e-brand-b-2" type="submit" id="checkout-btn"
                                                    disabled>PEMBAYARAN</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section 3 ======-->
        </form>
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

            function formatRupiah(angka, prefix) {
                var number_string = angka.toString().replace(/[^,\d]/g, ''),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
            }

            function updateCartTotal() {
                var total = 0;
                $('.check-box input[type="checkbox"]:checked').each(function() {
                    var $row = $(this).closest('tr');
                    var quantity = parseInt($row.find('.input-counter__text').val());
                    var price = parseFloat($row.find('.input-counter__text').data('price'));
                    total += quantity * price;
                });

                $('#cart-total').text(formatRupiah(total, 'Rp '));

                if (total > 0) {
                    $('#checkout-btn').prop('disabled', false);
                } else {
                    $('#checkout-btn').prop('disabled', true);
                }
            }

            $('body').on('change', '.check-box input[type="checkbox"]', function() {
                updateCartTotal();
            });

            $('body').on('change', '.input-counter__text', function() {
                var $this = $(this);
                var id = $this.data('id');
                var newQuantity = $this.val();

                $.ajax({
                    url: '/keranjang/edit/' + id,
                    type: 'POST',
                    data: {
                        id: id,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        console.log(response.message);
                        updateCartTotal();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            $('body').on('click', '.remove', function(e) {
                e.preventDefault();
                var $this = $(this);
                var id = $this.data('id');

                $.ajax({
                    url: '/keranjang/hapus/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        $this.closest('tr').remove();
                        console.log(response.message);
                        updateCartTotal();
                        updateCartCount();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            updateCartTotal();
        });
    </script>
@endsection
