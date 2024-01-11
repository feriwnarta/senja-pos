<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ $title ?? 'Page Title' }}</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
                crossorigin="anonymous">
        @notifyCss
        {{--
        <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/css-pos/font-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/navbar-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/header-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/menu-order-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/menu-order-modal-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/icon-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/active-order-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/sidebar-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/active-shift-pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/css-pos/select-customer-modal-pos.css') }}">

        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('data-table/datatables.js') }}"></script>
        {{-- <script src="{{ asset('js/toast.js') }}"></script>--}}

        </script>


        @livewireStyles


</head>

<body>

        {{ $slot }}

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
                integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
                crossorigin="anonymous"></script>
        <script data-navigate-once src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
                integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
                crossorigin="anonymous"></script>
        <script src="{{ asset('js/select-all.js') }}"></script>
        <script src="{{ asset('js/popup-on-save.js') }}"></script>
        @yield('footer-script')
</body>

</html>