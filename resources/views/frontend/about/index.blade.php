@extends('layouts.frontend.main')
@section('title', 'Tentang')
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
                                    <a href="{{ route('about.index') }}">@yield('title')</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== End - Section 1 ======-->


        <!--====== Section 2 ======-->
        <div class="u-s-p-b-60">

            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="about">
                                <div class="about__container">
                                    <div class="about__info">
                                        <h2 class="about__h2">Selamat Datang di {{ config('app.name') }}!</h2>
                                        <div class="about__p-wrap">
                                            <p class="about__p">Selamat datang di Vape Store Kami! Kami menyediakan berbagai
                                                macam produk vapor berkualitas untuk pengalaman yang lebih menyenangkan.
                                                Temukan berbagai pilihan vapor, aksesoris, dan peralatan terkait yang dapat
                                                memenuhi kebutuhan Anda.
                                                Untuk informasi lebih lanjut, Anda bisa mengikuti kami di Instagram <a
                                                    href="https://www.instagram.com/markas_vapor18/"
                                                    target="_blank">@markas_vapor18</a>.
                                                Pelayanan ramah dan pengiriman cepat adalah prioritas kami untuk memastikan
                                                kepuasan pelanggan. Nikmati pengalaman berbelanja yang mudah dan
                                                menyenangkan hanya di Vape Store Kami.</p>
                                        </div>


                                        <a class="about__link btn--e-secondary" href="{{ route('shop.index') }}">Belanja
                                            Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 2 ======-->


        <!--====== Section 11 ======-->
        <div class="u-s-p-b-90 u-s-m-b-30">

            <!--====== Section Intro ======-->
            <div class="section__intro u-s-m-b-46">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section__text-wrap">
                                <h1 class="section__heading u-c-secondary u-s-m-b-12">PENILAIAN PELANGGAN</h1>
                                <span class="section__span u-c-silver">APA YANG PELANGGAN KATAKAN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--====== End - Section Intro ======-->


            <!--====== Section Content ======-->
            <div class="section__content">
                <div class="container">
                    <!--====== Testimonial Slider ======-->
                    <div class="slider-fouc">
                        <div class="owl-carousel" id="testimonial-slider">
                            @foreach ($testimoni as $row)
                                <div class="testimonial">
                                    <div class="testimonial__img-wrap">
                                        <img class="testimonial__img"
                                            src="{{ $row->user->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . $row->user->first_name . ' ' . $row->user->last_name : asset('storage/avatar/' . $row->user->avatar) }}"
                                            alt="">
                                    </div>
                                    <div class="testimonial__content-wrap">

                                        <span class="testimonial__double-quote"><i class="fas fa-quote-right"></i></span>
                                        <blockquote class="testimonial__block-quote">
                                            <p>{{ $row->comment }}</p>
                                        </blockquote>

                                        <span
                                            class="testimonial__author">{{ $row->user->first_name . ' ' . $row->user->last_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!--====== End - Testimonial Slider ======-->
                </div>
            </div>
            <!--====== End - Section Content ======-->
        </div>
        <!--====== End - Section 11 ======-->
    </div>
    <!--====== End - App Content ======-->
@endsection
