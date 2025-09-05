@component("ui.boxlink", [
    "scheme" => $scheme ?? null,
    "href" => $href,
    "external" => $external ?? false
])
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-center shrink-0 w-10 h-10 bg-secondary-500 rounded-full shadow">
            @include($icon, [
                "class" => "w-2/3 h-2/3 fill-surface-900"
            ])
        </div>

        @if(isset($external) && $external)
            @include("icons.externalurl")
        @endif
    </div>

    <p class="text-xl font-bold">
        {{ $title }}
    </p>

    <p>
        {{ $description }}
    </p>
@endcomponent
