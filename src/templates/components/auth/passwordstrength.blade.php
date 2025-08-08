<span class="block w-full mt-2 bg-surface-400 border border-surface-400 border-2 rounded-full">
    <span class="block w-2 h-1 rounded-full data-[strength='0']:bg-danger-600 data-[strength='1']:bg-warning-600 data-[strength='2']:bg-safe-700 transition-all"
          id="password-strength-indicator-bar"
          data-strength="0"></span>
</span>

<div class="password-requirements text-surface-500 mb-2">
    <p>
        <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe-500 data-[met='true']:before:content-['✔'] transition-all"
              id="password-requirement-length" data-regex=".{8,}">
            {{ t("At least 8 characters") }}
        </span>
        <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe-500 data-[met='true']:before:content-['✔'] transition-all"
              id="password-requirement-uppercase" data-regex="[A-Z]">
            {{ t("Uppercase letters") }}
        </span>
        <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe-500 data-[met='true']:before:content-['✔'] transition-all"
              id="password-requirement-lowercase" data-regex="[a-z]">
            {{ t("Lowercase letters") }}
        </span>
        <span class="password-requirement block before:content-['✘'] data-[met='true']:text-safe-500 data-[met='true']:before:content-['✔'] transition-all"
              id="password-requirement-number" data-regex="[\d\W]">
            {{ t("Numbers or special characters") }}
        </span>
    </p>
</div>
