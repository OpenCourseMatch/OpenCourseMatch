@component("components.layout.appshell", [
    "title" => t("Participants and tutors"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        {{ t("Group actions") }}
    </h1>

    <form method="post" action="{{ Router::generate("group-actions-action") }}" data-redirect="{{ Router::generate("users-overview") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="group" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Group") }}
            </label>
            <select id="group"
                    name="group"
                    class="{{ TailwindUtil::$input }}">
                <option value="">{{ t("Default group") }}</option>
                @foreach($groups as $group)
                    <option value="{{ $group->getId() }}"
                            @if(!empty($user) && $user->getGroupId() === $group->getId())
                                selected
                            @endif>
                        {{ $group->getName() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-row gap-4">
            <div class="w-1/2">
                <div class="{{ TailwindUtil::inputGroup() }} mb-2">
                    <input id="reset-password"
                           name="reset-password"
                           type="checkbox"
                           value="1"
                           class="{{ TailwindUtil::$checkbox }}">
                    <label for="reset-password" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("Reset password") }}
                    </label>
                </div>

                <div class="{{ TailwindUtil::inputGroup() }} mb-2">
                    <label for="new-password" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("New password") }}
                        ({{ t("Leave empty to generate automatically") }})
                    </label>
                    <input id="new-password"
                           name="new-password"
                           type="password"
                           class="{{ TailwindUtil::$input }}"
                           value=""
                           minlength="8"
                           maxlength="256"
                           placeholder="{{ t("New password") }}">
                </div>
            </div>

            <div class="w-1/2">
                <div class="{{ TailwindUtil::inputGroup() }} mb-2">
                    <input id="change-group"
                           name="change-group"
                           type="checkbox"
                           value="1"
                           class="{{ TailwindUtil::$checkbox }}">
                    <label for="change-group" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("Change group") }}
                    </label>
                </div>

                <div class="{{ TailwindUtil::inputGroup() }} mb-2">
                    <label for="new-group" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("New group") }}
                    </label>
                    <select id="new-group"
                            name="new-group"
                            class="{{ TailwindUtil::$input }}">
                        <option value="">{{ t("Default group") }}</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->getId() }}"
                                    @if(!empty($user) && $user->getGroupId() === $group->getId())
                                        selected
                                    @endif>
                                {{ $group->getName() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.save")
            {{ t("Save") }}
        </button>

        <button type="button"
                id="delete-user"
                class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                data-delete-href="{{ Router::generate("group-actions-delete") }}">
            @include("components.icons.delete")
            {{ t("Delete") }}
        </button>
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        {{-- TODO: Group actions js --}}
    </script>
@endcomponent
