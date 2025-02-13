<!DOCTYPE html>
<html lang="en">
    <head>
        {{-- Encoding --}}
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{-- Browser tab --}}
        <title>Internal Error</title>

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ Router::staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        @if(!Config::$APP_SETTINGS["PRODUCTION"])
            <script src="{{ Router::staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="overflow-x-hidden bg-[#202020]">
        <main class="px-content-padding-sm mt-4 md:px-content-padding-md lg:px-content-padding-lg min-h-[90vh]">
            <h1 class="mb-4 text-4xl font-bold text-danger">
                {{ $exceptionName }}
            </h1>

            <h2 class="mb-4 text-2xl font-bold text-[#f45c4a]">
                {{ $exceptionMessage }}
            </h2>

            @foreach($trace as $traceItem)
                <div class="mb-4">
                    <div class="px-8 py-4 bg-[#f45c4a] text-[#202020] rounded-t">
                        <span class="font-bold">
                            {{ substr($traceItem["file"], 5) }}:{{ $traceItem["line"] }}
                        </span>
                        @if(isset($traceItem["function"]))
                            <span>
                                in {{ $traceItem["function"] }}()
                            </span>
                        @endif
                    </div>

                    <div class="px-8 py-4 font-[#202020] bg-[#f0f0f0] rounded-b">
                        @php
                            $fileContents = file($traceItem["file"]);
                            $startLine = max(0, ($traceItem["line"] - 1) - 3);
                            $endLine = min(count($fileContents) - 1, ($traceItem["line"] - 1) + 3);
                        @endphp

                        <code class="text-[#202020]">
                            @for($i = $startLine; $i <= $endLine; $i++)
                                <span class="@if($i === $traceItem["line"] - 1) bg-[#f45c4a80] rounded @endif break-all flex">
                                    @if($i === $traceItem["line"] - 1)
                                        <span class="whitespace-pre"> >  </span>
                                    @else
                                        <span class="whitespace-pre">    </span>
                                    @endif
                                    <span class="whitespace-pre">@for($j = 0; $j < strlen(strval($endLine + 1)) - strlen(strval($i + 1)); $j++) @endfor{{ $i + 1 }} | </span>
                                    <span class="whitespace-pre">{{ $fileContents[$i] }}</span>
                                </span>
                            @endfor
                        </code>
                    </div>
                </div>
            @endforeach
        </main>
    </body>
</html>
