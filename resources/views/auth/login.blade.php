@extends('layouts.auth.app')
@section('title', 'Login')
@section('content')
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    <img src="{{ asset('backend/assets') }}/images/auth/auth-cover-login-bg.svg" alt=""
                        class="img-fluid">
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                        <img src="{{ asset('backend/assets') }}/images/logo.png" alt="" class="img-fluid" style="max-width: 150%;">
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">@yield('title')</h2>
                    <h4 class="fs-13 fw-bold mb-2">Login ke akun anda</h4>
                    <form id="form" class="w-100 mt-4 pt-2">
                        <div class="mb-4">
                            <input type="text" class="form-control" placeholder="Email atau No telepon" name="username"
                                id="username" autofocus>
                            <small class="text-danger errorUsername mt-2"></small>
                        </div>
                        <div class="mb-3 generate-pass">
                            <div class="input-group field">
                                <input type="password" class="form-control password" id="password" name="password"
                                    placeholder="Password">
                                <div class="input-group-text border-start bg-gray-2 c-pointer show-pass"
                                    data-bs-toggle="tooltip" title="Lihat/Sembunyi Sandi"><i></i></div>
                            </div>
                            <small class="text-danger errorPassword mt-2"></small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label c-pointer" for="remember">Ingat saya</label>
                                </div>
                            </div>
                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="fs-11 text-primary">Lupa password?</a>
                                @endif
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" id="login" class="btn btn-lg btn-primary w-100">Login</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted">
                        <span> Tidak memiliki akun?</span>
                        <a href="{{ route('register') }}" class="fw-bold">Register</a>
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
                    url: "{{ route('login') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#login').attr('disable', 'disabled');
                        $('#login').text('Proses...');
                    },
                    complete: function() {
                        $('#login').removeAttr('disable');
                        $('#login').text('Login');
                    },
                    success: function(response) {
                        if (response.errors) {

                            if (response.errors.username) {
                                $('#username').addClass('is-invalid');
                                $('.errorUsername').html(response.errors.username);
                            } else {
                                $('#username').removeClass('is-invalid');
                                $('.errorUsername').html('');
                            }

                            if (response.errors.password) {
                                $('#password').addClass('is-invalid');
                                $('.errorPassword').html(response.errors.password);
                            } else {
                                $('#password').removeClass('is-invalid');
                                $('.errorPassword').html('');
                            }

                        } else if (response.NoUsername || response.NonActiveUsername || response
                            .WrongPassword) {
                            let errorMessage = '';
                            if (response.NoUsername) {
                                errorMessage = response.NoUsername.message;
                            } else if (response.NonActiveUsername) {
                                errorMessage = response.NonActiveUsername.message;
                            } else if (response.WrongPassword) {
                                errorMessage = response.WrongPassword.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: errorMessage,
                            });

                            if (response.NoUsername || response.NonActiveUsername) {
                                $('#username').val('');
                            }
                            if (response.WrongPassword || response.NoUsername || response
                                .NonActiveUsername) {
                                $('#password').val('');
                            }
                        } else {
                            window.location.href = response.redirect;
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    },
                });
            });
        })
    </script>
@endsection
