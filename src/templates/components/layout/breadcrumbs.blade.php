@if(!empty($breadcrumbs))
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($breadcrumbs as $i => $breadcrumb)
            <a href="{{ $breadcrumb["link"] }}"
               class="hover:text-font transition-colors @if($i === count($breadcrumbs) - 1) text-font @else text-gray @endif"
            >
                <div class="inline-flex justify-center inline-block">
                    @if(isset($breadcrumb["iconComponent"]))
                        @include($breadcrumb["iconComponent"], [
                            "class" => ""
                        ])
                    @endif

                    {{ $breadcrumb["name"] }}
                </div>
            </a>
            @if($i < count($breadcrumbs) - 1)
                <span class="text-font">
                    /
                </span>
            @endif
        @endforeach
    </div>
@endif
