<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.">
        <meta name="keywords" content="filament, laravel, filament plugin, record navigation, next record, previous record, filament php, admin panel">
        <meta name="author" content="Nben Malla">
        <link rel="canonical" href="https://rnd.nben.com.np/">

        <meta property="og:type" content="website">
        <meta property="og:url" content="https://rnd.nben.com.np/">
        <meta property="og:title" content="Filament Record Navigation — For Filament PHP">
        <meta property="og:description" content="Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.">
        <meta property="og:image" content="https://cdn.rnd.nben.com.np/media/company/record-navigation.webp">
        <meta property="og:image:width" content="1280">
        <meta property="og:image:height" content="720">
        <meta property="og:image:alt" content="Filament Record Navigation package preview">
        <meta property="og:locale" content="en_US">
        <meta property="og:site_name" content="Filament Record Navigation">

        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="rnd.nben.com.np">
        <meta name="twitter:url" content="https://rnd.nben.com.np/">
        <meta name="twitter:title" content="Filament Record Navigation — For Filament PHP">
        <meta name="twitter:description" content="Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.">
        <meta name="twitter:image" content="https://cdn.rnd.nben.com.np/media/company/record-navigation.webp">

        <link rel="icon" href="https://cdn.rnd.nben.com.np/media/company/favicon.ico" sizes="any">
        <link rel="icon" href="https://cdn.rnd.nben.com.np/media/company/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="https://cdn.rnd.nben.com.np/media/company/favicon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
