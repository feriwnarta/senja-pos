<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/point-of-sales.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/poppins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('data-table/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/spacer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('data-table/datatables.js') }}"></script>
    {{--        <script src="{{ asset('js/toast.js') }}"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    @livewireStyles
</head>
<body>
{{ $slot }}

@livewireScripts
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('bootstrap-5.0.2/js/bootstrap.js') }}"></script>


@yield('footer-script')
</body>
</html>
