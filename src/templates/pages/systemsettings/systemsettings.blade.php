@component("shells.console", [
    "title" => t("System settings"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("System settings") }}
    </h1>

    <div class="mb-4">
        @component("ui.helpbox")
            {{ t("Configure OpenCourseMatch to your organization's needs.") }}
        @endcomponent
    </div>

    <form method="post" action="{{ Router->generate("system-settings-save") }}">
        @foreach($settings as $setting)
            <div class="{{ TailwindUtil::inputGroup() }} mb-2">
                <label for="{{ $setting->getKey() }}" class="{{ TailwindUtil::$inputLabel }}" data-required>
                    {{ $defaultValues[$setting->getKey()]["name"] }}
                </label>
                <input id="{{ $setting->getKey() }}"
                       name="{{ $setting->getKey() }}"
                       type="text"
                       class="{{ TailwindUtil::$input }}"
                       value="{{ $setting->getValue() ?? "" }}"
                       placeholder="{{ $defaultValues[$setting->getKey()]["name"] }}"
                       maxlength="512"
                       required>
            </div>
        @endforeach

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.buttonload")
            @include("icons.save")
            {{ t("Save") }}
        </button>
    </form>
@endcomponent
