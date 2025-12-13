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
                                                                    data-max="{{ $item->product->stock }}"
                                                                    data-stock="{{ $item->product->stock }}"
                                                                    data-id="{{ $item->id }}"
                                                                    data-price="{{ $item->product->after_price }}">

                                                                <span class="input-counter__plus fas fa-plus"></span>
                                                            </div>
                                                            <!--====== End - Input Counter ======-->

                                                            <small class="d-block text-muted mt-1">
                                                                Stok: {{ $item->product->stock }}
                                                            </small>
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

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
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
                var hasInvalidStock = false;

                $('.check-box input[type="checkbox"]:checked').each(function() {
                    var $row = $(this).closest('tr');
                    var $input = $row.find('.input-counter__text');
                    var quantity = parseInt($input.val());
                    var price = parseFloat($input.data('price'));
                    var stock = parseInt($input.data('stock'));

                    if (quantity > stock) {
                        hasInvalidStock = true;
                    } else {
                        total += quantity * price;
                    }
                });

                $('#cart-total').text(formatRupiah(total, 'Rp '));

                if (total > 0 && !hasInvalidStock) {
                    $('#checkout-btn').prop('disabled', false);
                } else {
                    $('#checkout-btn').prop('disabled', true);
                }
            }

            $('body').on('change', '.check-box input[type="checkbox"]', function() {
                updateCartTotal();
            });

            // Override event handler untuk tombol plus
            $('body').off('click', '.input-counter__plus').on('click', '.input-counter__plus', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $input = $(this).siblings('.input-counter__text');
                var currentQty = parseInt($input.val()) || 0;
                var stock = parseInt($input.data('stock'));
                var min = parseInt($input.data('min')) || 1;

                if (currentQty >= stock) {
                    Toast.fire({
                        icon: 'warning',
                        title: `Stok tersedia hanya ${stock} item`
                    });
                    return false;
                }

                var newQty = currentQty + 1;
                if (newQty > stock) {
                    newQty = stock;
                    Toast.fire({
                        icon: 'warning',
                        title: `Stok tersedia hanya ${stock} item`
                    });
                }

                $input.val(newQty);
                $input.trigger('change');
            });

            // Override event handler untuk tombol minus
            $('body').off('click', '.input-counter__minus').on('click', '.input-counter__minus', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $input = $(this).siblings('.input-counter__text');
                var currentQty = parseInt($input.val()) || 1;
                var min = parseInt($input.data('min')) || 1;

                if (currentQty > min) {
                    $input.val(currentQty - 1);
                    $input.trigger('change');
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Jumlah minimal adalah 1 item'
                    });
                }
            });

            // Validasi saat input berubah (manual input atau dari tombol +/-)
            $('body').off('input change', '.input-counter__text').on('input change', '.input-counter__text',
                function(e) {
                    var $this = $(this);
                    var id = $this.data('id');
                    var inputValue = $this.val().replace(/[^0-9]/g, ''); // Hanya angka
                    var newQuantity = parseInt(inputValue) || 1;
                    var stock = parseInt($this.data('stock'));
                    var min = parseInt($this.data('min')) || 1;
                    var productName = $this.closest('tr').find('.table-p__name a').text();
                    var hasStockIssue = false;

                    // Validasi minimal
                    if (newQuantity < min) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Jumlah minimal adalah 1 item'
                        });
                        $this.val(min);
                        newQuantity = min;
                        return;
                    }

                    // Validasi stok
                    if (newQuantity > stock) {
                        hasStockIssue = true;
                        $this.val(stock);
                        newQuantity = stock;

                        // Tampilkan notifikasi stok tidak cukup DULU
                        Toast.fire({
                            icon: 'error',
                            title: 'Stok Tidak Cukup!',
                            text: `${productName} hanya tersedia ${stock} item`
                        }).then(function() {
                            // SETELAH notifikasi stok selesai, baru update ke server
                            if (e.type === 'change') {
                                updateCartToServer($this, id, newQuantity);
                            }
                        });

                        if (e.type !== 'change') {
                            updateCartTotal();
                        }
                        return;
                    }

                    // Jika tidak ada masalah stok, langsung update
                    if (e.type === 'change') {
                        updateCartToServer($this, id, newQuantity, !hasStockIssue);
                    } else {
                        updateCartTotal();
                    }
                });

            // Fungsi terpisah untuk update cart ke server
            function updateCartToServer($input, id, quantity, showSuccessToast = true) {
                $.ajax({
                    url: '/keranjang/edit/' + id,
                    type: 'POST',
                    data: {
                        id: id,
                        quantity: quantity
                    },
                    success: function(response) {
                        console.log(response.message);
                        if (showSuccessToast) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Keranjang diperbarui'
                            });
                        }
                        updateCartTotal();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status === 422) {
                            let errorMsg = xhr.responseJSON.message || 'Stok tidak mencukupi';
                            Toast.fire({
                                icon: 'error',
                                title: errorMsg
                            });
                            var stock = parseInt($input.data('stock'));
                            $input.val(stock);
                            updateCartTotal();
                        } else {
                            console.error(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            }

            // Prevent non-numeric input
            $('body').on('keypress', '.input-counter__text', function(e) {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
            });

            $('body').on('click', '.remove', function(e) {
                e.preventDefault();
                var $this = $(this);
                var id = $this.data('id');
                var productName = $this.closest('tr').find('.table-p__name a').text();

                Swal.fire({
                    title: 'Hapus Item?',
                    text: `Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/keranjang/hapus/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                $this.closest('tr').remove();
                                console.log(response.message);
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Item berhasil dihapus'
                                });
                                updateCartTotal();
                                updateCartCount();

                                // Cek apakah keranjang kosong
                                if ($('.table-p tbody tr').length === 0) {
                                    $('.table-p tbody').html(
                                        '<tr><td colspan="5" class="text-center">Data tidak tersedia</td></tr>'
                                    );
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Gagal menghapus item'
                                });
                                console.error(xhr.status + "\n" + xhr.responseText +
                                    "\n" + thrownError);
                            }
                        });
                    }
                });
            });

            // Validasi sebelum checkout
            $('body').on('submit', '.f-cart', function(e) {
                var hasSelectedItems = $('.check-box input[type="checkbox"]:checked').length > 0;
                var hasInvalidStock = false;
                var invalidItems = [];

                if (!hasSelectedItems) {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'warning',
                        title: 'Pilih minimal 1 item untuk checkout'
                    });
                    return false;
                }

                // Cek setiap item yang dipilih
                $('.check-box input[type="checkbox"]:checked').each(function() {
                    var $row = $(this).closest('tr');
                    var $input = $row.find('.input-counter__text');
                    var quantity = parseInt($input.val());
                    var stock = parseInt($input.data('stock'));
                    var productName = $row.find('.table-p__name a').text();

                    if (quantity > stock || stock === 0) {
                        hasInvalidStock = true;
                        invalidItems.push({
                            name: productName,
                            quantity: quantity,
                            stock: stock
                        });
                    }
                });

                if (hasInvalidStock) {
                    e.preventDefault();

                    let errorMessage = 'Item berikut melebihi stok yang tersedia:<br><br>';
                    invalidItems.forEach(function(item) {
                        if (item.stock === 0) {
                            errorMessage += `<strong>${item.name}</strong>: Stok habis<br>`;
                        } else {
                            errorMessage +=
                                `<strong>${item.name}</strong>: Anda pilih ${item.quantity}, tersedia ${item.stock}<br>`;
                        }
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Mencukupi',
                        html: errorMessage,
                        confirmButtonColor: '#3085d6',
                    });
                    return false;
                }
            });

            updateCartTotal();
        });
    </script>
@endsection
