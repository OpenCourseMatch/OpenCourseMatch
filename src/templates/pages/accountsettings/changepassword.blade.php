@component("shells.auth", ["title" => t("Change password")])
    <p class="mb-2">
        {{ t("Please enter your current password and confirm your new one.") }}
    </p>

    <form method="post" action="{{ Router->generate("account-settings-change-password-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-4">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="current-password"
                   data-required>
                {{ t("Current password") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="current-password"
                   id="current-password"
                   placeholder="{{ t("Current password") }}"
                   minlength="8"
                   maxlength="256"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="new-password"
                   data-required>
                {{ t("New password") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="new-password"
                   id="new-password"
                   placeholder="{{ t("New password") }}"
                   minlength="8"
                   maxlength="256"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="new-password-repeat"
                   data-required>
                {{ t("New password (repeat)") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="new-password-repeat"
                   id="new-password-repeat"
                   placeholder="{{ t("New password (repeat)") }}"
                   minlength="8"
                   maxlength="256"
                   required>
        </div>

        @include("ui.auth.passwordstrength")

        <button class="{{ TailwindUtil::button(true) }} w-full mb-2 gap-2"
                type="submit">
            @include("icons.buttonload")
            {{ t("Change password") }}
        </button>
    </form>

    <script type="module">
        import * as PasswordStrength from "{{ Router->staticFilePath("js/auth/PasswordStrength.js") }}";
        PasswordStrength.init("new-password");
    </script>
@endcomponent
