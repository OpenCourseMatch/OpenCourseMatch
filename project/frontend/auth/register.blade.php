@component("components.layout.authshell")
    <p class="mb-2">
        {{ t("Already have an account?") }}
        <a class="text-primary hover:text-primary-effect cursor-pointer transition-all"
           href="{{ Router::generate("auth-login") }}">
            {{ t("Click here to log in.") }}
        </a>
    </p>

    <form method="post" action="{{ Router::generate("auth-register-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="email"
                   data-required>
                {{ t("Email") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="email"
                   name="email"
                   id="email"
                   placeholder="{{ t("Email") }}"
                   required
                   @if(isset($email)) value="{{ $email }}" @endif>
        </div>

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
                   required
                   pattern="(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,15}"
                   @if(isset($username)) value="{{ $username }}" @endif>
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
                   required
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}">
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="password-repeat"
                   data-required>
                {{ t("Password (repeat)") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="password-repeat"
                   id="password-repeat"
                   placeholder="{{ t("Password (repeat)") }}"
                   required
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}">
        </div>

        <span class="block w-full mt-2 bg-gray-light border border-gray-light border-2 rounded-full">
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

        <button class="{{ TailwindUtil::button(true) }} w-full">
            {{ t("Register") }}
        </button>
    </form>

    <p class="text-sm text-gray">
        {{ t("Upon registration, you will receive an email with a link. Please open that link to verify your email address and to get access to your account.") }}
    </p>

    <script type="module">
        import PasswordStrength from "{{ Router::staticFilePath("js/auth/PasswordStrength.js") }}";
        PasswordStrength.init();
    </script>
@endcomponent
