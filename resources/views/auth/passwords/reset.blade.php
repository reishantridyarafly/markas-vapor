@extends('layouts.auth.app')
@section('title', 'Reset Password')
@section('content')
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    <img src="{{ asset('backend/assets') }}/images/auth/auth-cover-resetting-bg.svg" alt=""
                        class="img-fluid">
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                        <img src="{{ asset('backend/assets') }}/images/logo.png" alt="" class="img-fluid"
                            style="max-width: 150%;">
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">Reset Password</h2>
                    <p class="fs-12 fw-medium text-muted">Masukkan email Anda dan tautan reset akan dikirimkan. </p>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-4">
                            <input type="email" class="form-control  @error('email') is-invalid @enderror"
                                value="{{ $email ?? old('email') }}" autocomplete="email" autofocus placeholder="Email"
                                id="email" name="email">
                            @error('email')
                                <small class="text-danger mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-4 generate-pass">
                            <div class="input-group field">
                                <input type="password" class="form-control password" id="password" name="password"
                                    placeholder="Password">
                                <div class="input-group-text border-start bg-gray-2 c-pointer show-pass"
                                    data-bs-toggle="tooltip" title="Lihat/Sembunyi Sandi"><i></i></div>
                            </div>
                            @error('password')
                                <small class="text-danger mt-2">{{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="mb-4 generate-pass">
                            <div class="input-group field">
                                <input type="password" class="form-control password" id="password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi Password">
                                <div class="input-group-text border-start bg-gray-2 c-pointer show-pass"
                                    data-bs-toggle="tooltip" title="Lihat/Sembunyi Sandi"><i></i></div>
                                @error('password')
                                    <small class="text-danger mt-2">{{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Simpan Perubahan</button>
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
