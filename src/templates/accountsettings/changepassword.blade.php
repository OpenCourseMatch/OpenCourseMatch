@component("components.layout.authshell", ["title" => t("Change password")])
    <p class="mb-2">
        {{ t("Please enter your current password and confirm your new one.") }}
    </p>

    <form method="post" action="{{ Router::generate("account-settings-change-password-action") }}">
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

        <span class="block w-full mt-2 bg-gray-light border border-2 border-gray-light rounded-full">
            <span class="block w-2 h-1 rounded-full data-[strength='0']:bg-danger data-[strength='1']:bg-warning data-[strength='2']:bg-safe transition-all"
                  id="password-strength-indicator-bar"
                  data-strength="0"></span>
        </span>

        <div class="password-requirements text-font-light mb-2">
            <p>
                <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe data-[met='true']:before:content-['✔'] transition-all"
                      id="password-requirement-length" data-regex=".{8,}">
                    {{ t("At least 8 characters") }}
                </span>
                <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe data-[met='true']:before:content-['✔'] transition-all"
                      id="password-requirement-uppercase" data-regex="[A-Z]">
                    {{ t("Uppercase letters") }}
                </span>
                <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe data-[met='true']:before:content-['✔'] transition-alle"
                      id="password-requirement-lowercase" data-regex="[a-z]">
                    {{ t("Lowercase letters") }}
                </span>
                <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe data-[met='true']:before:content-['✔'] transition-all"
                      id="password-requirement-number" data-regex="[\d\W]">
                    {{ t("Numbers or special characters") }}
                </span>
            </p>
        </div>

        <button class="{{ TailwindUtil::button(true) }} w-full mb-2 gap-2"
                type="submit">
            @include("components.icons.buttonload")
            {{ t("Change password") }}
        </button>
    </form>

    <script type="module">
        import * as PasswordStrength from "{{ Router::staticFilePath("js/auth/PasswordStrength.js") }}";
        PasswordStrength.init("new-password");
    </script>
@endcomponent
