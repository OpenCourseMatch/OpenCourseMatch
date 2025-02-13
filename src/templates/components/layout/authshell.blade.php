<!DOCTYPE html>
<html lang="{{ Translator::getLocaleForHtmlLang() }}" class="h-full">
    <head>
        {{-- Encoding --}}
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{-- Browser tab --}}
        <title>@if(!empty($title)){{ $title }} - @endif{{ Config::$APP_SETTINGS["APP_NAME"] }}</title>
        <link rel="icon" href="{{ Config::$APP_SETTINGS["APP_FAVICON"] }}" type="image/x-icon">

        {{-- Basic SEO --}}
        <meta name="description" content="{{ SEO::getDescription() }}">
        <meta name="keywords" content="{{ SEO::getKeywords() }}">
        <meta name="author" content="{{ Config::$APP_SETTINGS["APP_AUTHOR"] }}">

        {{-- OpenGraph SEO --}}
        <meta property="og:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config::$APP_SETTINGS["APP_NAME"] }}">
        <meta property="og:description" content="{{ SEO::getDescription() }}">
        <meta property="og:image" content="{{ SEO::getImagePreview() }}">
        <meta property="og:url" content="{{ Router::getCalledURL() }}">
        @if(!empty(SEO::getOgSiteName()))
            <meta property="og:site_name" content="{{ SEO::getOgSiteName() }}">
        @endif
        <meta property="og:type" content="website">

        {{-- Twitter SEO --}}
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config::$APP_SETTINGS["APP_NAME"] }}">
        <meta name="twitter:description" content="{{ SEO::getDescription() }}">
        <meta name="twitter:image" content="{{ SEO::getImagePreview() }}">
        <meta name="twitter:url" content="{{ Router::getCalledURL() }}">
        @if(!empty(SEO::getTwitterSite()))
            <meta name="twitter:site" content="{{ SEO::getTwitterSite() }}">
        @endif
        @if(!empty(SEO::getTwitterCreator()))
            <meta name="twitter:creator" content="{{ SEO::getTwitterCreator() }}">
        @endif

        {{-- Indexing --}}
        <meta name="robots" content="{{ SEO::getRobots() }}">
        <meta name="revisit-after" content="{{ SEO::getRevisitAfter() }}">

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ Router::staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        <script src="{{ Router::staticFilePath("js/lib/jQuery.min.js") }}"></script>
        @if(!Config::$APP_SETTINGS["PRODUCTION"])
            <script src="{{ Router::staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="flex flex-row h-full overflow-x-hidden bg-gradient-to-br from-primary to-primary-effect text-font">
        <div class="flex flex-col items-center justify-center w-full md:w-1/2 h-full py-16 bg-background float-right">
            <div class="w-10/12 sm:w-2/3 md:w-1/2 overflow-y-auto">
                <header class="mb-8">
                    {{-- Logo --}}
                    <a href="{{ Config::$APP_SETTINGS["APP_URL"] }}">
                        <img src="{{ Router::staticFilePath("img/logo.svg") }}"
                             alt="Logo"
                             class="w-auto h-16 mb-8 rounded"
                        >
                        <h1>
                            {{ Config::$APP_SETTINGS["APP_NAME"] }}
                        </h1>
                    </a>
                </header>

                @include("components.layout.infomessagelist")

                <main>
                    {!! $slot !!}
                </main>
            </div>
        </div>

        <script type="module">
            import * as ButtonLoad from "{{ Router::staticFilePath("js/ButtonLoad.js") }}";
            import * as DateFormatter from "{{ Router::staticFilePath("js/DateFormatter.js") }}";
            ButtonLoad.init();
            DateFormatter.init();
        </script>
    </body>
</html>
