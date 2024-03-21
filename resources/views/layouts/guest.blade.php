<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="/icons/icone.png" type="image/x-icon">
        <title>{{ config('app.name', 'Shopin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 big-div-bg">


            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <div class="text-center" style="text-align: center;">
                    <a href="/">
                        <img src="/icons/logo.png" style="width: 50% !important">
                    </a>
                </div>
                <br>
                {{ $slot }}
            </div>
        </div>
    </body>

    <style>
        .big-div-bg{
            background: url('/icons/closeup-delivery-man-closing-carboard-box-with-tape-while-preparing-packages-shipment.webp')no-repeat;
            background-size: cover;
        }
    </style>

</html>
