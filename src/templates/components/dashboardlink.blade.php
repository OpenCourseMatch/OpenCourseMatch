<a class="flex flex-col w-full p-4 gap-2 bg-primary bg-opacity-20 rounded border border-2 border-primary hover:scale-[1.025] transition-all"
   href="{{ $href }}"
   @if(isset($external) && $external) target="_blank" @endif>
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-center shrink-0 w-10 h-10 bg-secondary rounded-full">
            @include($icon, [
                "class" => "w-2/3 h-2/3 fill-secondary-font"
            ])
        </div>

        @if(isset($external) && $external)
            @include("components.icons.externalurl")
        @endif
    </div>

    <p class="text-xl font-bold">
        {{ $title }}
    </p>

    <p>
        {{ $description }}
    </p>
</a>
