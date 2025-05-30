@component("components.layout.appshell")
    {{-- Content from /static/md/index.md --}}
    @include("components.markdown", [
        "path" => __APP_DIR__ . "/public/static/md/index.md"
    ])

    <a class="{{ TailwindUtil::button() }} mt-2 flex gap-2"
       href="{{ Router::generate("auth-login") }}">
        {{ t("Log in") }}
        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M607.46-480 298.85-788.62q-8.85-8.84-8.73-21.26.11-12.43 8.96-21.27 8.84-8.85 21.27-8.85 12.42 0 21.27 8.85l305.46 305.69q9.69 9.69 14.15 21.61 4.46 11.93 4.46 23.85 0 11.92-4.46 23.85-4.46 11.92-14.15 21.61l-305.7 305.69q-8.84 8.85-21.15 8.73-12.31-.11-21.15-8.96-8.85-8.84-8.85-21.27 0-12.42 8.85-21.27L607.46-480Z"/></svg>
    </a>
@endcomponent
