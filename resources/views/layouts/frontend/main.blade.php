<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="UTF-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets') }}/images/logo.png">
    <title>{{ config('app.name') }} | @yield('title')</title>

    <!--====== Google Font ======-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">

    <!--====== Vendor Css ======-->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/vendor.css">

    <!--====== Utility-Spacing ======-->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/utility.css">

    <!--====== App ======-->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/app.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="config">
    <div class="preloader is-active">
        <div class="preloader__wrap">

            <img class="preloader__img" src="{{ asset('frontend/assets') }}/images/preloader.png" alt="">
        </div>
    </div>

    <!--====== Main App ======-->
    <div id="app">

        @include('layouts.frontend.header')

        @yield('content')

        @include('layouts.frontend.footer')
    </div>
    <!--====== End - Main App ======-->

    <!--====== Vendor Js ======-->
    <script src="{{ asset('frontend/assets') }}/js/vendor.js"></script>

    <!--====== jQuery Shopnav plugin ======-->
    <script src="{{ asset('frontend/assets') }}/js/jquery.shopnav.js"></script>

    <!--====== App ======-->
    <script src="{{ asset('frontend/assets') }}/js/app.js"></script>

    @yield('script')

    <script>
        function updateCartCount() {
            $.ajax({
                type: "GET",
                url: "{{ route('cart.count') }}",
                dataType: 'json',
                success: function(response) {
                    $('#cart-count').text(response.count);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

        updateCartCount();

        $('body').on('click', '#logout-link', function() {
            Swal.fire({
                title: 'Keluar',
                text: "Apakah kamu yakin?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal',
            }).then((willLogout) => {
                if (willLogout.isConfirmed) {
                    logoutUser();
                }
            });
        })

        function logoutUser() {
            $.ajax({
                url: "{{ route('logout') }}",
                type: 'POST',
                data: $('#logout-form').serialize(),
                success: function(response) {
                    window.location.href = "{{ route('login') }}";
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }
    </script>
</body>

</html>
