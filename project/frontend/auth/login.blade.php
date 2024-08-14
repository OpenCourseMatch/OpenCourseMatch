@component("components.layout.authshell")
    <p class="mb-2">
        {{ t("Please enter your account credentials to log in.") }}
    </p>

    <form method="post" action="{{ Router::generate("auth-login-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="username"
                   data-required>
                {{ t("Username") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="text"
                   name="username"
                   id="username"
                   placeholder="{{ t("Username") }}"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="password"
                   data-required>
                {{ t("Password") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="password"
                   id="password"
                   placeholder="{{ t("Password") }}"
                   required>
        </div>

        <button class="{{ TailwindUtil::button(true) }} w-full mb-2 gap-2"
                type="submit">
            @include("components.icons.buttonload")
            {{ t("Log in") }}
        </button>
    </form>
@endcomponent
