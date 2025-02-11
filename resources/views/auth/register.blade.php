@extends('layouts.auth.app')
@section('title', 'Register')
@section('content')
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    <img src="{{ asset('backend/assets') }}/images/auth/auth-cover-register-bg.svg" alt=""
                        class="img-fluid">
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-80 mb-5">
                        <img src="{{ asset('backend/assets') }}/images/logo.png" alt="" class="img-fluid" > 
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">@yield('title')</h2>
                    <h4 class="fs-13 fw-bold mb-2">Daftar Sekarang untuk Mengakses Fitur Lengkap</h4>
                    <form id="form" class="w-100 mt-4 pt-2">
                        <div class="mb-4">
                            <input type="text" class="form-control" placeholder="Nama Depan" name="first_name"
                                id="first_name">
                            <small class="text-danger errorFirstName mt-2"></small>
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control" placeholder="Nama Belakang" name="last_name"
                                id="last_name">
                            <small class="text-danger errorLastName mt-2"></small>
                        </div>
                        <div class="mb-4">
                            <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                            <small class="text-danger errorEmail mt-2"></small>
                        </div>
                        <div class="mb-4">
                            <input type="number" class="form-control" placeholder="No Telepon" name="telephone"
                                id="telephone">
                            <small class="text-danger errorTelephone mt-2"></small>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <select name="province" id="province" data-select2-selector="icon"
                                            class="form-control">
                                            <option value="">Provinsi</option>
                                            @foreach ($provinces as $row)
                                                <option value="{{ $row->id }}">
                                                    {{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger errorProvince mt-2"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <select name="city" id="city" class="form-control"
                                            data-select2-selector="icon">
                                            <option value="">Kota</option>
                                        </select>
                                        <small class="text-danger errorCity mt-2"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control" placeholder="Jalan" id="street" name="street">
                            <small class="text-danger errorStreet mt-2"></small>
                        </div>
                        <div class="mb-4">
                            <textarea name="detail_address" id="detail_address" rows="2" class="form-control" placeholder="Detail Alamat"></textarea>
                            <small class="text-danger errorDetailAddress mt-2"></small>
                        </div>
                        <div class="mb-4 generate-pass">
                            <div class="input-group field">
                                <input type="password" class="form-control password" id="password" name="password"
                                    placeholder="Password">
                                <div class="input-group-text c-pointer gen-pass" data-bs-toggle="tooltip"
                                    title="Generate Password"><i class="feather-hash"></i></div>
                                <div class="input-group-text border-start bg-gray-2 c-pointer show-pass"
                                    data-bs-toggle="tooltip" title="Lihat/Sembunyi Sandi"><i></i></div>
                            </div>
                            <div class="progress-bar mt-2">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <small class="text-danger errorPassword mt-2"></small>
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control" placeholder="Konfirmasi Password"
                                id="password_confirmation" name="password_confirmation">
                            <small class="text-danger errorConfirmPassword mt-2"></small>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100" id="register">Buat Akun</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted">
                        <span>Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="fw-bold">Login</a>
                    </div>
                </div>
            </div>
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
                    url: "{{ route('register') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Proses...',
                            html: 'Silakan tunggu, sedang memproses.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    complete: function() {
                        Swal.close();
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.first_name) {
                                $('#first_name').addClass('is-invalid');
                                $('.errorFirstName').html(response.errors.first_name);
                            } else {
                                $('#first_name').removeClass('is-invalid');
                                $('.errorFirstName').html('');
                            }

                            if (response.errors.last_name) {
                                $('#last_name').addClass('is-invalid');
                                $('.errorLastName').html(response.errors.last_name);
                            } else {
                                $('#last_name').removeClass('is-invalid');
                                $('.errorLastName').html('');
                            }

                            if (response.errors.email) {
                                $('#email').addClass('is-invalid');
                                $('.errorEmail').html(response.errors.email);
                            } else {
                                $('#email').removeClass('is-invalid');
                                $('.errorEmail').html('');
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

                            if (response.errors.password) {
                                $('#password').addClass('is-invalid');
                                $('.errorPassword').html(response.errors.password);
                            } else {
                                $('#password').removeClass('is-invalid');
                                $('.errorPassword').html('');
                            }

                            if (response.errors.password_confirmation) {
                                $('#password_confirmation').addClass('is-invalid');
                                $('.errorConfirmPassword').html(response.errors
                                    .password_confirmation);
                            } else {
                                $('#password_confirmation').removeClass('is-invalid');
                                $('.errorConfirmPassword').html('');
                            }

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('login') }}";
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            $('#province').on('change', function() {
                let id_province = $('#province').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('register.get-city') }}",
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
        });
    </script>
@endsection
