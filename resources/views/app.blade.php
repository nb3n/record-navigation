@php
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Schema;

$graph = new Graph();

$graph->webSite()
    ->setProperty('@id', 'https://rnd.nben.com.np/#website')
    ->url('https://rnd.nben.com.np/')
    ->name('Filament Record Navigation')
    ->description('Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.')
    ->inLanguage('en-US')
    ->publisher($graph->person());

$graph->webPage()
    ->setProperty('@id', 'https://rnd.nben.com.np/#webpage')
    ->url('https://rnd.nben.com.np/')
    ->name('Filament Record Navigation — For Filament PHP')
    ->description('Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.')
    ->inLanguage('en-US')
    ->isPartOf(Schema::webSite()->setProperty('@id', 'https://rnd.nben.com.np/#website'))
    ->image('https://cdn.rnd.nben.com.np/media/company/record-navigation.webp')
    ->datePublished('2025-05-27')
    ->dateModified('2026-04-13');

$graph->softwareApplication()
    ->setProperty('@id', 'https://rnd.nben.com.np/#software')
    ->name('Filament Record Navigation')
    ->description('Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.')
    ->url('https://rnd.nben.com.np/')
    ->applicationCategory('DeveloperApplication')
    ->operatingSystem('Any')
    ->softwareVersion('2.1.0')
    ->license('https://opensource.org/licenses/MIT')
    ->author($graph->person())
    ->mainEntityOfPage(
        Schema::webPage()->setProperty('@id', 'https://rnd.nben.com.np/#webpage')
    )
    ->offers(
        Schema::offer()
            ->price('0.00')
            ->priceCurrency('USD')
            ->availability('https://schema.org/InStock')
            ->url('https://packagist.org/packages/nben/filament-record-nav')
    )
    ->aggregateRating(
        Schema::aggregateRating()
            ->ratingValue('5')
            ->bestRating('5')
            ->worstRating('1')
            ->ratingCount('1')
    );
$graph->softwareSourceCode()
    ->setProperty('@id', 'https://rnd.nben.com.np/#sourcecode')
    ->name('Filament Record Navigation')
    ->codeRepository('https://github.com/nb3n/filament-record-nav')
    ->programmingLanguage('PHP')
    ->runtimePlatform('Laravel')
    ->license('https://opensource.org/licenses/MIT')
    ->author($graph->person());

$graph->breadcrumbList()
    ->itemListElement([
        Schema::listItem()->position(1)->name('Home')->item('https://rnd.nben.com.np/'),
    ]);

$graph->person()
    ->setProperty('@id', 'https://rnd.nben.com.np/#author')
    ->name('Nben Malla')
    ->alternateName('nb3n')
    ->email('hello@nben.com.np')
    ->url('https://nben.com.np')
    ->image('https://cdn.rnd.nben.com.np/media/company/nben-malla.webp')
    ->sameAs([
        'https://github.com/nb3n',
        'https://www.linkedin.com/in/nben',
        'https://www.instagram.com/nb3n.m',
        'https://www.wikidata.org/wiki/Q131932191',
        'https://orcid.org/0009-0006-8795-8757',
        'https://packagist.org/packages/nben/filament-record-nav',
        'https://filamentphp.com/plugins/nben-malla-record-navigation',
    ]);

$graph->fAQPage()
    ->mainEntity([
        Schema::question()
            ->name('Do I need to add a trait to my page class?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'No. The trait is completely optional. Just drop PreviousRecordAction and NextRecordAction into getHeaderActions() and everything works out of the box using the config-driven defaults.',
            ]),
        Schema::question()
            ->name('Which versions of Filament and PHP are supported?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'The package requires PHP 8.2 or higher and supports Filament 4.x and 5.x. For Filament 3.x support, use v1.x of the package.',
            ]),
        Schema::question()
            ->name('How does record ordering work?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'By default records are ordered by the id column. You can change the order column and sort directions by publishing the config file and updating the order_column, previous_direction, and next_direction values.',
            ]),
        Schema::question()
            ->name('Can I filter which records are included in the navigation?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'Yes. Add the WithRecordNavigation trait to your page and override getPreviousRecord() and getNextRecord() with your own query logic, for example filtering by status, tenant, or any custom scope.',
            ]),
        Schema::question()
            ->name('Can I navigate to the edit page instead of the view page?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'Yes. Pass NavigationPage::Edit to the navigateTo() method on either action. Each action can target a different page type independently, and custom routes registered in getPages() are also supported via NavigationPage::custom().',
            ]),
        Schema::question()
            ->name('Will the buttons break if there is no adjacent record?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'No. The buttons automatically disable and turn gray when there is no previous or next record, so users always get clear visual feedback at the boundaries.',
            ]),
        Schema::question()
            ->name('How many database queries does each action fire per render?')
            ->setProperty('acceptedAnswer', [
                '@type' => 'Answer',
                'text' => 'Exactly one per action per render. The package caches the resolved adjacent record internally so the color, disabled state, and URL closures all share the same query result.',
            ]),
    ]);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Add next and previous record navigation to your Filament PHP admin panel. Zero config, no trait required, works out of the box.">
        <meta name="keywords" content="filament, laravel, filament plugin, record navigation, next record, previous record, filament php, admin panel">
        <meta name="author" content="Nben Malla">
        <link rel="canonical" href="https://rnd.nben.com.np/">

        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">
        <meta name="theme-color" content="#ffffff">
        <link rel="preconnect" href="https://cdn.rnd.nben.com.np">

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

        {!! $graph->toScript() !!}
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
