@component("components.layout.appshell", [
    "title" => t("Administrators"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        @if(!empty($user))
            {{ t("Edit administrator \$\$name\$\$", ["name" => $user->getFullName()]) }}
        @else
            {{ t("Create administrator") }}
        @endif
    </h1>

    <form method="post" action="{{ Router::generate("administrators-save") }}" data-redirect="{{ Router::generate("administrators-overview") }}">
        @if(!empty($user))
            <input type="hidden" name="user" value="{{ $user->getId() }}">
        @endif

        <div class="flex flex-col md:flex-row gap-2 mb-2">
            <div class="{{ TailwindUtil::inputGroup() }}">
                <label for="firstName" class="{{ TailwindUtil::$inputLabel }}" data-required>
                    {{ t("First name") }}
                </label>
                <input id="firstName"
                       name="firstName"
                       type="text"
                       class="{{ TailwindUtil::$input }}"
                       value="{{ !empty($user) ? $user->getFirstName() : "" }}"
                       placeholder="{{ t("First name") }}"
                       maxlength="64"
                       required>
            </div>
            <div class="{{ TailwindUtil::inputGroup() }}">
                <label for="lastName" class="{{ TailwindUtil::$inputLabel }}" data-required>
                    {{ t("Last name") }}
                </label>
                <input id="lastName"
                       name="lastName"
                       type="text"
                       class="{{ TailwindUtil::$input }}"
                       value="{{ !empty($user) ? $user->getLastName() : "" }}"
                       placeholder="{{ t("Last name") }}"
                       maxlength="64"
                       required>
            </div>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="password" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Password") }}
                @if(!empty($user))
                    ({{ t("Leave empty to keep old one") }})
                @else()
                    ({{ t("Leave empty to generate automatically") }})
                @endif
            </label>
            <input id="password"
                   name="password"
                   type="password"
                   class="{{ TailwindUtil::$input }}"
                   value=""
                   minlength="8"
                   maxlength="256"
                   placeholder="{{ t("Password") }}">
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.save")
            {{ t("Save") }}
        </button>

        @if(!empty($user))
            <button type="button"
                    id="delete-user"
                    class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                    data-delete-href="{{ Router::generate("administrators-delete", ["user" => $user->getId()]) }}">
                @include("components.icons.buttonload")
                @include("components.icons.delete")
                {{ t("Delete") }}
            </button>
        @endif
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        import * as AdministratorsEdit from "{{ Router::staticFilePath("js/administrators/edit.js") }}";
        AdministratorsEdit.init();
    </script>
@endcomponent
