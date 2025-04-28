<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Ielts</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('build/assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('build/assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('build/assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('build/assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('build/assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('build/assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('build/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/config.js') }}"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    @include('layouts.partials.styles')

    <style>
        @yield('css')
    </style>

    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>


<body>

<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <!-- ===============================================-->
    <!--    Sidebar-->
    <!-- ===============================================-->
    @include('layouts.partials.sidebar')

    <!-- ===============================================-->
    <!--    Navbar-->
    <!-- ===============================================-->
    @include('layouts.partials.navbar')

    <div class="content">
        <!-- ===============================================-->
        <!--    Breadcrumb-->
        <!-- ===============================================-->
        @include('layouts.partials.breadcrumb')

        <!-- ===============================================-->
        <!--    Alert-->
        <!-- ===============================================-->
        @include('layouts.partials.alert')

        <!-- ===============================================-->
        <!--    Content area -->
        <!-- ===============================================-->
        @yield('contents')

        <!-- ===============================================-->
        <!--    Footer-->
        <!-- ===============================================-->
        @include('layouts.partials.footer')

    </div>
</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
@include('layouts.partials.scripts')

@yield('js')
</body>

</html>