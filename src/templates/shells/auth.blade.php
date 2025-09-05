<!DOCTYPE html>
<html class="h-full">
    <head>
        {{-- Encoding --}}
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @include("shells.generic.metatags", [
            "title" => $title ?? null,
            "hideFromSeo" => $hideFromSeo ?? true
        ])

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        @if(!Config->isProduction())
            <script src="{{ Router->staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="flex flex-row h-full overflow-x-hidden bg-linear-to-br from-primary-500 to-primary-700">
        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/Translator.js") }}";
            init("{{ Router->generate("translations-api") }}");
        </script>

        <div class="flex flex-col items-center justify-center w-full md:w-1/2 h-full py-16 bg-surface-100 text-surface-900 float-right">
            <div class="w-10/12 sm:w-2/3 md:w-1/2 overflow-y-auto">
                <header class="mb-8">
                    {{-- Logo --}}
                    <a href="{{ Config->getAppUrl() }}">
                        <img src="{{ Router->staticFilePath("img/logo.svg") }}"
                             alt="Logo"
                             class="w-auto h-16 mb-8 rounded"
                        >
                        <h1 class="truncate">
                            {{ Config->getAppName() }}
                        </h1>
                    </a>
                </header>

                @include("shells.generic.infomessagelist")

                <main>
                    {!! $slot !!}
                </main>
            </div>
        </div>

        <script type="module">
            import * as ButtonLoad from "{{ Router->staticFilePath("js/ButtonLoad.js") }}";
            import * as DateFormatter from "{{ Router->staticFilePath("js/DateFormatter.js") }}";
            ButtonLoad.init();
            DateFormatter.init();
        </script>
    </body>
</html>
