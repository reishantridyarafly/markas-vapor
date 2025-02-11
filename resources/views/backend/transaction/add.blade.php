@extends('layouts.backend.main')
@section('title', 'Tambah Transaksi')
@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">@yield('title')</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">Transaksi</a></li>
                        <li class="breadcrumb-item">@yield('title')</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->

            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <form id="form">
                        <div class="col-xl-12">
                            <div class="card stretch stretch-full">
                                <div class="card-body">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="transaction_date" class="col-form-label">Tanggal Transaksi</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="datetime-local" id="transaction_date" name="transaction_date"
                                                class="form-control" value="{{ date('Y-m-d\TH:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="transaction_date" class="col-form-label">Pelanggan</label>
                                        </div>
                                        <div class="col-9">
                                            <select class="form-control" data-select2-selector="icon" name="customers"
                                                id="customers" required>
                                                <option value="0" data-customer-name="Umum" data-icon="feather-user"
                                                    selected>Umum</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        data-customer-name="{{ $user->first_name . ' ' . $user->last_name }}"
                                                        data-icon="feather-user">
                                                        {{ $user->first_name . ' ' . $user->last_name }}
                                                    </option>
                                                @endforeach
                                                <input type="hidden" name="customer_name" id="customer_name"
                                                    value="Umum">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card invoice-container">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <div class="table-responsive">
                                            <table class="table table-bordered overflow-hidden" id="tab_logic">
                                                <thead>
                                                    <tr class="single-item">
                                                        <th class="text-center wd-200">Product</th>
                                                        <th class="text-center wd-150">Harga</th>
                                                        <th class="text-center wd-150">Qty</th>
                                                        <th class="text-center wd-150">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="addr0">
                                                        <td>
                                                            <select class="form-control product-select" name="products[]"
                                                                required>
                                                                <option value="1" data-icon="feather-archive"
                                                                    data-price="0" data-stock="0">-- Pilih Produk --
                                                                </option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-icon="feather-archive"
                                                                        data-price="{{ $product->after_price }}"
                                                                        data-stock="{{ $product->stock }}">
                                                                        {{ $product->name }} | {{ $product->ram }} |
                                                                        {{ $product->capacity }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="price[]" class="form-control price"
                                                                readonly></td>
                                                        <td><input type="number" name="qty[]" class="form-control qty"
                                                                step="1" min="1" value="1" required></td>
                                                        <td><input type="text" name="total_items[]"
                                                                class="form-control total" readonly></td>
                                                    </tr>
                                                    <tr id="addr1"></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <button type="button" id="delete_row"
                                                class="btn btn-sm bg-soft-danger text-danger">Hapus</button>
                                            <button type="button" id="add_row" class="btn btn-sm btn-primary">Tambah
                                                Item</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card invoice-container">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="subtotal" class="col-form-label">Subtotal</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" id="subtotal" name="subtotal"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="discount" class="col-form-label">Diskon (%)</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="number" id="discount" name="discount" value="0"
                                                        min="0" max="100" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="total" class="col-form-label">Total Keseluruhan</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" id="total" name="total"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="type_payment" class="col-form-label">Pembayaran</label>
                                                </div>
                                                <div class="col-8">
                                                    <select class="form-control" data-select2-selector="icon"
                                                        name="type_payment" id="type_payment">
                                                        <option value="cash" data-icon="feather-dollar-sign" selected>
                                                            Uang
                                                            Tunai</option>
                                                        <option value="transfer" data-icon="feather-dollar-sign">Transfer
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="cash" class="col-form-label">Uang Tunai</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" id="cash" name="cash"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mb-3">
                                                <div class="col-4">
                                                    <label for="change" class="col-form-label">Kembalian</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" id="change" name="change" readonly
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-top-dashed">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="{{ route('transaction.create') }}" type="button"
                                                        class="btn btn-secondary w-100">Batal</a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-primary w-100"
                                                        id="payment">Pembayaran</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#customers').select2({
                theme: 'bootstrap4'
            });

            $('#customers').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var customerName = selectedOption.data('customer-name');
                $('#customer_name').val(customerName);
            });
        });


        $(document).on('input', '#cash', function() {
            var cashValue = unformatRupiah($(this).val());
            $(this).val(formatRupiah(cashValue));
        });

        function formatRupiah(amount) {
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            return formatter.format(amount).replace('IDR', 'Rp');
        }

        function unformatRupiah(rupiahString) {
            var numberString = rupiahString.replace(/[^\d,-]/g, '');
            var number = parseFloat(numberString.replace(/,/g,
                '.'));
            return !isNaN(number) ? number : 0;
        }

        function updateRowTotal(row) {
            var price = unformatRupiah(row.find('.price').val());
            var qty = parseFloat(row.find('.qty').val()) || 1;
            var total = price * qty;
            row.find('.total').val(formatRupiah(total));
            updateSubtotal();
        }

        function updateSubtotal() {
            var subtotal = 0;
            $('.total').each(function() {
                subtotal += unformatRupiah($(this).val());
            });
            $('#subtotal').val(formatRupiah(subtotal));
            updateTotal();
        }

        function updateTotal() {
            var subtotal = unformatRupiah($('#subtotal').val());
            var discountPercent = parseFloat($('#discount').val()) || 0;
            var discountAmount = subtotal * (discountPercent / 100);
            var total = subtotal - discountAmount;
            $('#total').val(formatRupiah(total.toFixed(2)));
            updateChange();
        }

        function updateChange() {
            var total = unformatRupiah($('#total').val());
            var cash = unformatRupiah($('#cash').val());

            if (!isNaN(total) && !isNaN(cash)) {
                var change = cash - total;
                $('#change').val(formatRupiah(change));
            } else {
                $('#change').val('');
            }
        }

        function initializeSelect2() {
            $('.product-select').select2({
                theme: 'bootstrap4'
            });
        }

        $(document).on('change', '.product-select', function() {
            var selectedOption = $(this).find('option:selected');
            var price = parseFloat(selectedOption.data('price')) || 0;
            $(this).closest('tr').find('.price').val(formatRupiah(price));
            updateRowTotal($(this).closest('tr'));

            var stock = parseInt(selectedOption.data('stock')) || 0;
            var quantity = parseInt($(this).closest('tr').find('.qty').val()) || 0;

            if (quantity > stock) {
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
                Toast.fire({
                    icon: "error",
                    title: "Stok tidak tersedia!"
                });
                $(this).closest('tr').find('.qty').val(stock);
                updateRowTotal($(this).closest('tr'));
            }
        });

        $(document).on('input', '.qty', function() {
            var row = $(this).closest('tr');
            var selectedOption = row.find('.product-select option:selected');
            var stock = parseInt(selectedOption.data('stock')) || 0;
            var quantity = parseInt($(this).val()) || 0;

            if (quantity > stock) {
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
                Toast.fire({
                    icon: "error",
                    title: "Stok tidak tersedia!"
                });
                $(this).val(stock);
                updateRowTotal(row);
            } else {
                updateRowTotal(row);
            }
        });

        $('#discount').on('input', updateTotal);
        $('#cash').on('input', updateChange);

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('transaction.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#payment').attr('disable', 'disabled');
                        $('#payment').text('Proses...');
                    },
                    complete: function() {
                        $('#payment').removeAttr('disable');
                        $('#payment').text('Pembayaran');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                        }).then(function() {
                            top.location.href = "{{ route('transaction.create') }}";
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }

                })
            });

            let i = 1;
            $("#add_row").click(function() {
                let newRow = `<tr id="addr${i}">
            <td>
                <select class="form-control product-select" name="products[]" required>
                    <option value="1" data-icon="feather-archive" data-price="0" data-stock="0">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-icon="feather-archive" data-price="{{ $product->after_price }}"  data-stock="{{ $product->stock }}">
                            {{ $product->name }} | {{ $product->ram }} | {{ $product->capacity }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="price[]" class="form-control price" readonly></td>
            <td><input type="number" name="qty[]" class="form-control qty" step="1" min="1" value="1" required></td>
            <td><input type="text" name="total_items[]" class="form-control total" readonly></td>
        </tr>`;
                $("#tab_logic tbody").append(newRow);
                initializeSelect2();
                i++;
            });

            $("#delete_row").click(function() {
                var rowCount = $("#tab_logic tbody tr").length;
                if (rowCount > 1) {
                    $("#tab_logic tbody tr:last").remove();
                    i--;
                    updateSubtotal();
                }
            });

            initializeSelect2();
            updateSubtotal();
        });
    </script>
@endsection
