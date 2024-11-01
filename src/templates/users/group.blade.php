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
                    <input id="resetPassword"
                           name="resetPassword"
                           type="checkbox"
                           value="1"
                           class="{{ TailwindUtil::$checkbox }}">
                    <label for="resetPassword" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("Reset password") }}
                    </label>
                </div>

                <div id="new-password-input" class="{{ TailwindUtil::inputGroup() }} mb-2 hidden">
                    <label for="newPassword" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("New password") }}
                        ({{ t("Leave empty to generate automatically") }})
                    </label>
                    <input id="newPassword"
                           name="newPassword"
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
                    <input id="changeGroup"
                           name="changeGroup"
                           type="checkbox"
                           value="1"
                           class="{{ TailwindUtil::$checkbox }}">
                    <label for="changeGroup" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("Change group") }}
                    </label>
                </div>

                <div id="new-group-selection" class="{{ TailwindUtil::inputGroup() }} mb-2 hidden">
                    <label for="newGroup" class="{{ TailwindUtil::$inputLabel }}">
                        {{ t("New group") }}
                    </label>
                    <select id="newGroup"
                            name="newGroup"
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
                id="delete-users"
                class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                data-delete-href="{{ Router::generate("group-actions-delete") }}"
                data-redirect="{{ Router::generate("users-overview") }}">
            @include("components.icons.buttonload")
            @include("components.icons.delete")
            {{ t("Delete") }}
        </button>
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        import * as GroupActions from "{{ Router::staticFilePath("js/users/group.js") }}";
        GroupActions.init();
    </script>
@endcomponent
