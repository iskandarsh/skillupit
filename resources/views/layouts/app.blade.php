<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CSS LIBRARY -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JQUERY (WAJIB PALING ATAS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- PLUGIN JQUERY -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- VITE -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GLOBAL JQUERY SAFE -->
    <script>
        window.$safe = jQuery.noConflict(true);
    </script>

</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen">

        @include('layouts.navigation')

        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

    </div>

    <!-- SAFE INIT PATTERN -->
    <script>
        $safe(document).ready(function() {
            console.log("🚀 jQuery Ready Safe Mode Active");

            // contoh global CSRF
            $safe.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        });
    </script>

</body>

</html>