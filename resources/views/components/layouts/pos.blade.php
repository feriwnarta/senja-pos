<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? 'Page Title' }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    @notifyCss
    {{--
        <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

    {{-- MICRO COMPONENT --}}
    <link rel="stylesheet" href="{{ asset('css/css-pos/font-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/icon-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/text-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/button-pos.css') }}">
    
    {{-- COMPONENT --}}
    <link rel="stylesheet" href="{{ asset('css/css-pos/navbar-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/header-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/sidebar-pos.css') }}">

    {{-- PAGES --}}
    <link rel="stylesheet" href="{{ asset('css/css-pos/menu-order-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/active-order-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/active-shift-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/active-shift-detail-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/riwayat-shift-detail-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/mutasi-pos.css') }}">

    {{-- MODALS --}}
    <link rel="stylesheet" href="{{ asset('css/css-pos/menu-order-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/select-customer-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/new-customer-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/akhiri-shift-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/shift-berakhir-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/pilih-meja-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/diskon-voucher-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/bayar-order-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/pembayaran-berhasil-modal-pos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-pos/pin-modal-pos.css') }}">


    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('data-table/datatables.js') }}"></script>
    {{-- <script src="{{ asset('js/toast.js') }}"></script> --}}

    @livewireStyles


</head>

<body>

    {{ $slot }}

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script data-navigate-once src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/popup-sidebar.js') }}" async></script>
    <script src="{{ asset('js/pin.js') }}" async></script>
    <script src="{{ asset('js/select-all.js') }}" async></script>
    {{-- <script src="{{ asset('js/date-format.js') }}" async data-navigate-once></script> --}}
    @yield('footer-script')
</body>

</html>
