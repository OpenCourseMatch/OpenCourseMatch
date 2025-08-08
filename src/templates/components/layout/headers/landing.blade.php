<div class="p-4 bg-surface-100">
    <header class="flex justify-between items-center max-w-screen-xl m-auto">
        {{-- Logo --}}
        <div class="whitespace-nowrap">
            <a href="{{ Router->generate(Config::$APP_SETTINGS["APP_URL"]) }}"
               class="flex justify-start items-center"
            >
                <img src="{{ Router->staticFilePath("img/logo.svg") }}"
                     alt="Logo"
                     class="h-12 w-auto rounded"
                >
                <span class="ml-2 font-bold">
                    {{ Config::$APP_SETTINGS["APP_NAME"] }}
                </span>
            </a>
        </div>

        {{-- Open sidebar button --}}
        <button class="cursor-pointer sidebar-toggle">
            @include("components.icons.menu", [ "class" => "w-6 h-6" ])
        </button>

        @component("components.layout.sidebars.landing")
            {{-- Sidebar navigation list --}}
            <nav>
                @include("components.layout.sidebars.landinglist")
            </nav>

            {{-- Account info --}}
            @include("components.layout.sidebars.accountinfo")
        @endcomponent
    </header>
</div>
