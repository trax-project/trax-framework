<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('img/apple-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <!-- Fonts and icons -->
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/roboto/roboto.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/material-icons/material-icons.css') }}">
    <!-- Source
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    -->

    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('js/lib/pace/themes/purple/pace-theme-flash.css') }}">
    <link rel="stylesheet" href="{{ asset('js/lib/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/lib/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/lib/DataTables/datatables.min.css') }}">
    
    <!-- Material Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('themes/'.config('trax.ui.theme').'/material-dashboard.min.css') }}">

    <!-- Don't move this after trax.ccs -->
    @yield('head')

    <!-- Trax CSS -->
    <link rel="stylesheet" href="{{ asset('themes/'.config('trax.ui.theme').'/trax/common.css?v=20190612') }}">
    <link rel="stylesheet" href="{{ asset('themes/'.config('trax.ui.theme').'/trax/style.css') }}">

    <!-- Title -->
    <title>
        {{ config('app.name') }}
        @if (isset($nav) && isset($nav->title))
            / {{ $nav->title }}
        @endif
    </title>

    <script>
        window.lang = {!! json_encode($lang) !!};
        window.app_url = "{{ url('/') }}/";
        @if (isset($user))
            window.user = {!! json_encode($user) !!};
        @endif
        @if (isset($data))
            window.data = {!! json_encode($data) !!};
        @endif
    </script>    

</head>
<body class="@yield('body-class') {{ isset($nav) && $nav->minisidebar ? 'sidebar-mini' : '' }}">

    @yield('layout')
    
    <!--   Core JS Files   -->
    <script src="{{ asset('js/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/lib/popper/popper.min.js') }}"></script>
    <script src="{{ asset('themes/'.config('trax.ui.theme').'/bootstrap-material-design.min.js') }}"></script>

    <!-- Plugins -->
    <script data-pace-options='{"ajax":{"trackMethods":["GET","POST"]}}' src="{{ asset('js/lib/pace/pace.min.js') }}"></script>
    <script src="{{ asset('js/lib/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/lib/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/lib/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/lib/sortablejs/Sortable.min.js') }}"></script>
    <script src="{{ asset('js/lib/garand-sticky/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/lib/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('js/lib/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('js/lib/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>

    <!-- Material Kit Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{{ asset('themes/'.config('trax.ui.theme').'/material-dashboard.js') }}"></script>

    <!-- Trax JS (VueJS) -->
    <script src="{{ traxMix('js/bootstrap.js') }}"></script>
    <script src="{{ traxMix('js/trax-ui.js') }}"></script>
    <script src="{{ traxMix('js/trax-notification.js') }}"></script>
    @yield('components')
    <script src="{{ traxMix('js/app.js') }}"></script>

    <!-- Page Specific -->
    @yield('scripts')
    
</body>
</html>
