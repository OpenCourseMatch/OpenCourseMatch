@component("components.layout.authshell")
    <p class="mb-2">
        {{ t("Don't have an account yet?") }}
        <a class="text-primary hover:text-primary-effect cursor-pointer transition-all"
           href="{{ Router::generate("auth-register") }}">
            {{ t("Click here to register.") }}
        </a>
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
                   pattern="(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,15}"
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

        <button class="{{ TailwindUtil::button(true) }} w-full mb-2">
            {{ t("Log in") }}
        </button>

        <a class="text-sm text-gray hover:text-gray-effect cursor-pointer transition-all"
           href="{{ Router::generate("auth-recovery-request") }}">
            {{ t("Forgot password?") }}
        </a>
    </form>
@endcomponent
