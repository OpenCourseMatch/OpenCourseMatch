<div class="p-4 bg-surface-100">
    <header class="flex justify-between items-center max-w-screen-xl m-auto">
        {{-- Logo --}}
        <div class="whitespace-nowrap">
            <a href="{{ Config->getAppUrl() }}"
               class="flex justify-start items-center"
            >
                <img src="{{ Router->staticFilePath("img/logo.svg") }}"
                     alt="Logo"
                     class="h-12 w-auto rounded"
                >
                <span class="ml-2 font-bold">
                    {{ Config->getAppName() }}
                </span>
            </a>
        </div>

        {{-- Open sidebar button --}}
        <button class="cursor-pointer sidebar-toggle">
            @include("icons.menu", [ "class" => "w-6 h-6" ])
        </button>

        @component("shells.sidebars.landing")
            {{-- Sidebar navigation list --}}
            <nav>
                @include("shells.sidebars.landinglist")
            </nav>

            {{-- Account info --}}
            @include("shells.sidebars.accountinfo")
        @endcomponent
    </header>
</div>
