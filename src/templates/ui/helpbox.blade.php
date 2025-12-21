@if(Auth->getLoggedInUser()?->getShowHelpBoxes())
    <div class="mb-4">
        @component("ui.box", ["scheme" => $scheme ?? BoxScheme::SURFACE])
            {!! $slot !!}
        @endcomponent
    </div>
@endif
