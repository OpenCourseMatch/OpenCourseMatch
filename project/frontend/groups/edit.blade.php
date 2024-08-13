@component("components.layout.appshell", ["title" => t("Groups")])
    <h1 class="mb-2">
        @if(!empty($group))
            {{ t("Edit group") }} „{{ $group->getName() }}”
        @else
            {{ t("Create group") }}
        @endif
    </h1>

    <form method="post" action="{{ Router::generate("groups-save") }}">
        @if(!empty($group))
            <input type="hidden" name="groupId" value="{{ $group->getId() }}">
        @endif

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="name" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Group name") }}
            </label>
            <input id="name"
                   name="name"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($group) ? $group->getName() : "" }}"
                   placeholder="{{ t("Group name") }}"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="clearance" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Clearance level") }}
            </label>
            <input id="clearance"
                   name="clearance"
                   type="number"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($group) ? $group->getClearance() : "" }}"
                   placeholder="{{ t("Clearance level") }}"
                   step="1"
                   required>
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.save")
            {{ t("Save") }}
        </button>

        @if(!empty($group))
            <button type="button"
                    id="delete-group"
                    class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                    data-delete-href="{{ Router::generate("groups-delete", ["groupId" => $group->getId()]) }}">
                @include("components.icons.delete")
                {{ t("Delete") }}
            </button>
        @endif
    </form>

    @include("components.modals.defaultabort")
    <script type="module">
        import GroupsEdit from "{{ Router::staticFilePath("js/groups/edit.js") }}";
        GroupsEdit.init();
    </script>
@endcomponent
