@extends('layouts.frontend.main')
@section('title', 'Pembayaran')
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
                                <li class="has-separator">
                                    <a href="{{ route('shop.index') }}">Belanja</a>
                                </li>
                                <li class="is-marked">
                                    <a href="{{ route('shop.detail', $product->slug) }}">{{ $product->name }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Section 1 ======-->

        <!--====== Section 3 ======-->
        <div class="u-s-p-b-60">

            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="checkout-f">
                        <form class="checkout-f__delivery" id="form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h1 class="checkout-f__h1">INFORMASI PENGIRIMAN</h1>
                                    <div class="u-s-m-b-30">
                                        <!--====== First Name, Last Name ======-->
                                        <div class="gl-inline">
                                            <div class="u-s-m-b-15">

                                                <label class="gl-label" for="billing-fname">NAMA DEPAN *</label>

                                                <input class="input-text input-text--primary-style" type="text"
                                                    id="billing-fname" value="{{ auth()->user()->first_name }}" disabled>
                                            </div>
                                            <div class="u-s-m-b-15">

                                                <label class="gl-label" for="billing-lname">NAMA BELAKANG *</label>

                                                <input class="input-text input-text--primary-style" type="text"
                                                    id="billing-lname" value="{{ auth()->user()->last_name }}" disabled>
                                            </div>
                                        </div>
                                        <!--====== End - First Name, Last Name ======-->


                                        <!--====== E-MAIL ======-->
                                        <div class="u-s-m-b-15">

                                            <label class="gl-label" for="billing-email">E-MAIL *</label>

                                            <input class="input-text input-text--primary-style" type="text"
                                                id="billing-email" value="{{ auth()->user()->email }}" disabled>
                                        </div>
                                        <!--====== End - E-MAIL ======-->


                                        <!--====== PHONE ======-->
                                        <div class="u-s-m-b-15">

                                            <label class="gl-label" for="billing-phone">NO TELEPON *</label>

                                            <input class="input-text input-text--primary-style" type="text"
                                                id="billing-phone" value="{{ auth()->user()->telephone }}" disabled>
                                            <input type="hidden" name="total_weight" id="total_weight"
                                                value="{{ $weight }}">
                                        </div>
                                        <!--====== End - PHONE ======-->

                                        <!--====== Country ======-->
                                        <div class="u-s-m-b-15">

                                            <!--====== Select Box ======-->

                                            <label class="gl-label" for="billing-country">ALAMAT *</label>
                                            <select class="select-box select-box--primary-style" id="address"
                                                name="address">
                                                <option value="">Pilih Alamat</option>
                                                @forelse ($addresses as $address)
                                                    <option value="{{ $address->id }}"
                                                        {{ $address->default_address == 0 ? 'selected' : '' }}>
                                                        {{ $address->detail_address }}
                                                    </option>
                                                @empty
                                                    <option value="">Data tidak tersedia</option>
                                                @endforelse
                                            </select>
                                            <small>Note: Silakan isi <a href="{{ route('address.index') }}">Alamat</a> jika
                                                masih kosong</small>
                                            <small class="text-danger errorAddress" style="color: red"></small>
                                            <!--====== End - Select Box ======-->
                                        </div>
                                        <!--====== End - Country ======-->


                                        <!--====== Town / City ======-->
                                        <div class="u-s-m-b-15">
                                            <label class="gl-label" for="province">PROVINSI *</label>
                                            <input class="input-text input-text--primary-style" type="text"
                                                id="province" name="province" disabled>
                                        </div>
                                        <!--====== End - Town / City ======-->


                                        <!--====== STATE/PROVINCE ======-->
                                        <div class="u-s-m-b-15">
                                            <!--====== Select Box ======-->
                                            <label class="gl-label" for="district">KOTA / KABUPATEN *</label>
                                            <input type="hidden" name="district_id" id="district_id" disabled>
                                            <input class="input-text input-text--primary-style" type="text"
                                                id="district" name="district" disabled>
                                            <!--====== End - Select Box ======-->
                                        </div>
                                        <!--====== End - STATE/PROVINCE ======-->

                                        <!--====== STATE/PROVINCE ======-->
                                        <div class="u-s-m-b-15">
                                            <!--====== Select Box ======-->
                                            <label class="gl-label" for="subdistrict">KECAMATAN *</label>
                                            <input type="hidden" name="subdistrict_id" id="subdistrict_id" disabled>
                                            <input class="input-text input-text--primary-style" type="text"
                                                id="subdistrict" name="subdistrict" disabled>
                                            <!--====== End - Select Box ======-->
                                        </div>
                                        <!--====== End - STATE/PROVINCE ======-->


                                        <!--====== STATE/PROVINCE ======-->
                                        <div class="u-s-m-b-15">
                                            <!--====== Select Box ======-->
                                            <label class="gl-label" for="billing-state">KURIR *</label>
                                            <select class="select-box select-box--primary-style" id="courier"
                                                name="courier">
                                                <option value="">Pilih Kurir</option>
                                                <option value="jne">JNE</option>
                                                <option value="pos">POS</option>
                                                <option value="tiki">TIKI</option>
                                            </select>
                                            <!--====== End - Select Box ======-->
                                            <small class="text-danger errorCourier" style="color: red"></small>
                                        </div>
                                        <!--====== End - STATE/PROVINCE ======-->

                                        <!--====== STATE/PROVINCE ======-->
                                        <div class="u-s-m-b-15">
                                            <!--====== Select Box ======-->
                                            <label class="gl-label" for="billing-state">ONGKOS KIRIM *</label>
                                            <select class="select-box select-box--primary-style" id="shipping_cost"
                                                name="shipping_cost">
                                                <option value="">Pilih Ongkos Kirim</option>
                                            </select>
                                            <small class="text-danger errorShippingCost" style="color: red"></small>
                                            <!--====== End - Select Box ======-->
                                        </div>
                                        <!--====== End - STATE/PROVINCE ======-->

                                        <div class="u-s-m-b-10">
                                            <label class="gl-label" for="order-note">PESAN</label>
                                            <textarea class="text-area text-area--primary-style" id="note" name="note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h1 class="checkout-f__h1">RINGKASAN PEMESANAN</h1>
                                    <!--====== Order Summary ======-->
                                    <div class="o-summary">
                                        <div class="o-summary__section u-s-m-b-30">
                                            <div class="o-summary__item-wrap gl-scroll">
                                                <div class="o-card">
                                                    <div class="o-card__flex">
                                                        <div class="o-card__img-wrap">

                                                            <img class="u-img-fluid"
                                                                src="{{ asset('storage/uploads/cover/' . $product->cover_photo) }}"
                                                                alt="">
                                                        </div>
                                                        <div class="o-card__info-wrap">

                                                            <span class="o-card__name">

                                                                <a
                                                                    href="{{ route('shop.detail', $product->slug) }}">{{ $product->name }}</a></span>

                                                            <input type="hidden" name="product_id" id="product_id"
                                                                value="{{ $product->id }}">
                                                            <input type="hidden" name="qty" id="qty"
                                                                value="{{ $qty }}">
                                                            <input type="hidden" name="unit_price" id="unit_price"
                                                                value="{{ $product->after_price }}">
                                                            <span class="o-card__quantity">Quantity x
                                                                {{ $qty }}</span>

                                                            <span
                                                                class="o-card__price">{{ 'Rp ' . number_format($product->after_price * $qty, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="o-summary__section u-s-m-b-30">
                                            <div class="o-summary__box">
                                                <table class="o-summary__table">
                                                    <tbody>
                                                        <tr>
                                                            <td>ONGKOS KIRIM</td>
                                                            <td id="total_ongkir_text"></td>
                                                            <input type="hidden" name="total_ongkir" id="total_ongkir">
                                                        </tr>
                                                        <tr>
                                                            <td>SUBTOTAL</td>
                                                            <td id="subtotal_text"></td>
                                                            <input type="hidden" name="subtotal" id="subtotal">
                                                        </tr>
                                                        <tr>
                                                            <td>TOTAL KESELURUHAN</td>
                                                            <td id="total_keseluruhan_text"></td>
                                                            <input type="hidden" name="total" id="total">
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="o-summary__section u-s-m-b-30">
                                            <div class="o-summary__box">
                                                <h1 class="checkout-f__h1">REKENING</h1>
                                                <div class="ship-b">
                                                    @foreach ($rekening as $row)
                                                        <div class="ship-b__box">
                                                            <span class="ship-b__text">{{ $row->name }}</span>
                                                            <span class="ship-b__text">{{ $row->bank_name }}</span>
                                                            <span class="ship-b__text">{{ $row->account_number }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="o-summary__section u-s-m-b-30">
                                            <div class="o-summary__box">
                                                <h1 class="checkout-f__h1">BUKTI PEMBAYARAN (<small
                                                        class="text-muted">Format: JPG, JPEG, PNG, WEBP, SVG</small>)</h1>
                                                <div class="ship-b">
                                                    <div class="u-s-m-b-15">
                                                        <!--====== Select Box ======-->
                                                        <div class="col-12">
                                                            <input type="file" class="form-control"
                                                                name="transfer_proof" id="transfer_proof"
                                                                accept="image/*">
                                                        </div>
                                                        <small class="text-danger errorTransferProof"
                                                            style="color: red"></small>
                                                        <!--====== End - Select Box ======-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="o-summary__section u-s-m-b-30">
                                            <div class="o-summary__box">
                                                <h1 class="checkout-f__h1">KONFIRMASI PEMBAYARAN</h1>
                                                <div>
                                                    <button class="btn btn--e-brand-b-2" id="checkout"
                                                        type="submit">PEMBAYARAN</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--====== End - Order Summary ======-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 3 ======-->
    </div>
    <!--====== End - App Content ======-->
@endsection

@section('script')
    <script>
        function formatCurrency(amount) {
            return 'Rp ' + parseFloat(amount).toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        }

        function resetCheckoutForm() {
            $('#province').val('');
            $('#district_id').val('');
            $('#district').val('');
            $('#subdistrict_id').val('');
            $('#subdistrict').val('');
            $('#courier').val('');
            $('#shipping_cost').empty().append('<option value="">Pilih kurir terlebih dahulu&hellip;</option>');
            updateTotalPayment(0);
        }

        function loadAddressDetails(addressId) {
            $.ajax({
                url: `/pembayaran/get-address-details/${addressId}`,
                type: 'POST',
                data: {
                    id: addressId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#province').val(response.province_name);
                        $('#district_id').val(response.district_id);
                        $('#district').val(response.district_name);
                        $('#subdistrict_id').val(response.subdistrict_id);
                        $('#subdistrict').val(response.subdistrict_name);

                        $('#courier').val('');
                        $('#shipping_cost').empty().append(
                            '<option value="">Pilih kurir terlebih dahulu&hellip;</option>');
                        updateTotalPayment(0);

                        // Clear errors
                        $('.errorAddress').html('');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Gagal memuat data alamat'
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengambil data alamat.'
                    });
                }
            });
        }

        function updateTotalPayment(ongkir) {
            let subtotal = parseFloat('{{ $subtotal }}');
            let total = subtotal + ongkir;

            $('#total_ongkir_text').text(formatCurrency(ongkir));
            $('#subtotal_text').text(formatCurrency(subtotal));
            $('#total_keseluruhan_text').text(formatCurrency(total));
            $('#total').val(total);
            $('#total_ongkir').val(ongkir);
            $('#subtotal').val(subtotal);
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var initialAddressId = $('#address').val();
            if (initialAddressId) {
                loadAddressDetails(initialAddressId);
            }

            $('#address').on('change', function() {
                var addressId = $(this).val();

                if (!addressId) {
                    resetCheckoutForm();
                    return;
                }

                loadAddressDetails(addressId);
            });

            var addressId = $('#address').val();
            if (addressId) {
                $.ajax({
                    url: "{{ url('/pembayaran/get-address-details/"+addressId+"') }}",
                    type: 'POST',
                    data: {
                        id: addressId
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#province').val(response.province_name);
                        $('#district_id').val(response.district_id);
                        $('#district').val(response.district_name);
                        $('#subdistrict_id').val(response.subdistrict_id);
                        $('#subdistrict').val(response.subdistrict_name);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }

            $('#address').on('change', function() {
                var addressId = $(this).val();
                if (addressId) {
                    $.ajax({
                        url: "{{ url('/pembayaran/get-address-details/"+addressId+"') }}",
                        type: 'POST',
                        data: {
                            id: addressId
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#province').val(response.province_name);
                            $('#district_id').val(response.district_id);
                            $('#district').val(response.district_name);
                            $('#subdistrict_id').val(response.subdistrict_id);
                            $('#subdistrict').val(response.subdistrict_name);
                            $('#shipping_cost').empty();
                        },
                    });
                } else {
                    $('#province').val('');
                    $('#district').val('');
                    $('#subdistrict').val('');
                }
            });

            $('#courier').on('change', function() {
                let districtId = $('#district_id').val();
                let subdistrictId = $('#subdistrict_id').val();
                let courier = $('#courier').val();
                let weight = $('#total_weight').val();

                $('.errorCourier').html('');

                if (!districtId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih alamat terlebih dahulu'
                    });
                    $(this).val('');
                    return;
                }

                if (!courier) {
                    $('#shipping_cost').empty().append(
                        '<option value="">Pilih kurir terlebih dahulu&hellip;</option>');
                    updateTotalPayment(0);
                    return;
                }

                // Show loading
                $('#shipping_cost').empty().append(
                    '<option value="">Memuat layanan pengiriman...</option>');
                $('#shipping_cost').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('checkout.check-ongkir') }}",
                    data: {
                        district_id: districtId,
                        courier: courier,
                        weight: weight
                    },
                    success: function(response) {
                        $('#shipping_cost').prop('disabled', false);
                        if (response.success) {
                            $('#shipping_cost').html(response.ongkir);
                        } else {
                            $('#shipping_cost').html(
                                '<option value="">Gagal memuat layanan</option>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memuat data ongkir'
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                        $('#shipping_cost').prop('disabled', false);
                        $('#shipping_cost').html('<option value="">Gagal memuat data</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengambil ongkir'
                        });
                    }
                });
            });

            $('#shipping_cost').on('change', function() {
                let shipping_cost = parseFloat($(this).val());

                $('.errorShippingCost').html('');

                if (!shipping_cost || isNaN(shipping_cost)) {
                    updateTotalPayment(0);
                    return;
                }

                updateTotalPayment(shipping_cost);
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('checkout.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#checkout').attr('disable', 'disabled');
                        $('#checkout').text('PROSES...');
                    },
                    complete: function() {
                        $('#checkout').removeAttr('disable');
                        $('#checkout').text('PEMBAYARAN');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.address) {
                                $('.errorAddress').html(response.errors.address);
                            } else {
                                $('.errorAddress').html('');
                            }

                            if (response.errors.courier) {
                                $('.errorCourier').html(response.errors.courier);
                            } else {
                                $('.errorCourier').html('');
                            }

                            if (response.errors.shipping_cost) {
                                $('.errorShippingCost').html(response.errors.shipping_cost);
                            } else {
                                $('.errorShippingCost').html('');
                            }

                            if (response.errors.transfer_proof) {
                                $('.errorTransferProof').html(response.errors.transfer_proof);
                            } else {
                                $('.errorTransferProof').html('');
                            }

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.message,
                            }).then(function() {
                                top.location.href = "{{ route('shop.index') }}";
                            });
                        }
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
