@extends('layouts.auth.app')
@section('title', 'Reset Password')
@section('content')
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    <img src="{{ asset('backend/assets') }}/images/auth/auth-cover-reset-bg.svg" alt=""
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
                    <p class="fs-12 fw-medium text-muted">Reset password Anda dengan mengikuti langkah-langkah berikut</p>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <input class="form-control  @error('email') is-invalid @enderror" placeholder="Email"
                                type="email" id="email" name="email" value="{{ old('email') }}"
                                autocomplete="email" autofocus>
                            @error('email')
                                <small class="text-danger errorPassword mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Reset</button>
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
