@extends('layouts.backend.main')
@section('title', 'Tambah Alamat')
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
                        <li class="breadcrumb-item"><a href="{{ route('address.index') }}">Alamat</a></li>
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
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body lead-status">
                                <form id="form">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="name" class="form-label">Nama <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name">
                                                <small class="text-danger errorName mt-2"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="telephone" class="form-label">No Telepon <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="telephone" name="telephone">
                                                <small class="text-danger errorTelephone mt-2"></small>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="province" class="form-label">Provinsi <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" data-select2-selector="icon" name="province"
                                                    id="province">
                                                    <option value="">-- Pilih Provinsi -- </option>
                                                    @foreach ($provinces as $row)
                                                        <option value="{{ $row->id }}">
                                                            {{ $row->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-danger errorProvince mt-2"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="city" class="form-label">Kota <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" data-select2-selector="icon" name="city"
                                                    id="city">
                                                    <option value="">-- Pilih Kota --</option>
                                                </select>
                                                <small class="text-danger errorCity mt-2"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="street" class="form-label">Jalan <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="street" name="street">
                                                <small class="text-danger errorStreet mt-2"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Detail Alamat <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="detail_address" id="detail_address" rows="5" class="form-control"></textarea>
                                                <small class="text-danger errorDetailAddress mt-2"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="default_address" name="default_address" value="0">
                                                    <label class="form-check-label" for="default_address">Atur sebagai alamat
                                                        utama</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="form-group mb-3 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary"
                                                    id="save">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection

@section('script')
    <script>
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
                    url: "{{ route('address.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#save').attr('disable', 'disabled');
                        $('#save').text('Proses...');
                    },
                    complete: function() {
                        $('#save').removeAttr('disable');
                        $('#save').text('Simpan');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }

                            if (response.errors.telephone) {
                                $('#telephone').addClass('is-invalid');
                                $('.errorTelephone').html(response.errors.telephone);
                            } else {
                                $('#telephone').removeClass('is-invalid');
                                $('.errorTelephone').html('');
                            }

                            if (response.errors.province) {
                                $('#province').addClass('is-invalid');
                                $('.errorProvince').html(response.errors.province);
                            } else {
                                $('#province').removeClass('is-invalid');
                                $('.errorProvince').html('');
                            }

                            if (response.errors.city) {
                                $('#city').addClass('is-invalid');
                                $('.errorCity').html(response.errors.city);
                            } else {
                                $('#city').removeClass('is-invalid');
                                $('.errorCity').html('');
                            }

                            if (response.errors.street) {
                                $('#street').addClass('is-invalid');
                                $('.errorStreet').html(response.errors.street);
                            } else {
                                $('#street').removeClass('is-invalid');
                                $('.errorStreet').html('');
                            }

                            if (response.errors.detail_address) {
                                $('#detail_address').addClass('is-invalid');
                                $('.errorDetailAddress').html(response.errors.detail_address);
                            } else {
                                $('#detail_address').removeClass('is-invalid');
                                $('.errorDetailAddress').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.message,
                            }).then(function() {
                                top.location.href = "{{ route('address.index') }}";
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

        $('#province').on('change', function() {
            let id_province = $('#province').val();

            $.ajax({
                type: "POST",
                url: "{{ route('address.get-city') }}",
                data: {
                    province_id: id_province
                },
                success: function(response) {
                    $('#city').html(response);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        });
    </script>
@endsection
