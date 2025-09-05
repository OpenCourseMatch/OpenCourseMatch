<!DOCTYPE html>
<html>
    <head>
        {{-- Encoding --}}
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @include("shells.generic.metatags", [
            "title" => $title ?? null,
            "hideFromSeo" => $hideFromSeo ?? false
        ])

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/lib/datatables.min.css") }}">
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        <script src="{{ Router->staticFilePath("js/lib/jquery.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/datatables.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/download.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/chart.umd.min.js") }}"></script>
        @if(!Config->isProduction())
            <script src="{{ Router->staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="bg-surface-100 text-surface-900 overflow-x-hidden">
        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/Translator.js") }}";
            init("{{ Router->generate("translations-api") }}");
        </script>

        @include("shells.headers.landing")

        <div class="px-4">
            <main class="max-w-screen-xl m-auto min-h-[90vh]">
                @include("shells.generic.infomessagelist")

                {!! $slot !!}
            </main>
        </div>

        @include("shells.footers.landing")

        <script type="module">
            import * as ButtonLoad from "{{ Router->staticFilePath("js/ButtonLoad.js") }}";
            import * as DateFormatter from "{{ Router->staticFilePath("js/DateFormatter.js") }}";
            ButtonLoad.init();
            DateFormatter.init();
        </script>
    </body>
</html>
