<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    {{-- Sneat Vendor & Core CSS for all pages --}}
    <link rel="stylesheet" href="/sneat/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="/sneat/assets/css/demo.css" />
    <link rel="stylesheet" href="/sneat/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="/sneat/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    @routes
    {{-- Load only CSS here to ensure styles are applied before page renders --}}
    @vite('resources/css/app.css')
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
    {{-- Load JS at end of body for Vite / Inertia pages --}}
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
</body>

</html>
