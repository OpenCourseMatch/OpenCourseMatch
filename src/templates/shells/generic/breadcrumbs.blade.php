@if(!empty($breadcrumbs))
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($breadcrumbs as $i => $breadcrumb)
            <a href="{{ $breadcrumb["link"] }}"
               class="hover:text-surface-900 transition-colors @if($i !== count($breadcrumbs) - 1) text-surface-500 @endif"
            >
                <div class="inline-flex justify-center inline-block">
                    @if(isset($breadcrumb["iconComponent"]))
                        @include($breadcrumb["iconComponent"])
                    @endif

                    {{ $breadcrumb["name"] }}
                </div>
            </a>
            @if($i < count($breadcrumbs) - 1)
                <span>
                    /
                </span>
            @endif
        @endforeach
    </div>
@endif
