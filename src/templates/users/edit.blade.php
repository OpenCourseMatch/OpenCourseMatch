@component("components.layout.appshell", [
    "title" => t("Participants and tutors"),
    "breadcrumbs" => $breadcrumbs ?? []
])
    <h1 class="mb-2">
        @if(!empty($user))
            {{ t("Edit user \$\$name\$\$", ["name" => $user->getFullName()]) }}
        @else
            {{ t("Create user") }}
        @endif
    </h1>

    <form method="post" action="{{ Router::generate("users-save") }}" data-redirect="{{ Router::generate("users-overview") }}">
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

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="leadingCourse" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Leading course (optional)") }}
            </label>
            <select id="leadingCourse"
                    name="leadingCourse"
                    class="{{ TailwindUtil::$input }}">
                <option value="">-</option>
                @foreach($courses as $course)
                    <option value="{{ $course->getId() }}"
                            @if(!empty($user) && $user->getLeadingCourseId() === $course->getId())
                                selected
                            @endif>
                        {{ $course->getTitle() }}
                        @if($course->getOrganizer() !== null)
                            ({{ $course->getOrganizer() }})
                        @endif
                    </option>
                @endforeach
            </select>
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
                    data-delete-href="{{ Router::generate("users-delete", ["user" => $user->getId()]) }}">
                @include("components.icons.delete")
                {{ t("Delete") }}
            </button>
        @endif
    </form>

    @if(!empty($user))
        <h2 class="mt-4 mb-2">
            {{ t("Chosen courses") }}
        </h2>
        <div class="flex flex-col gap-1">
            @foreach($user->getChoices() as $i => $choice)
                <span>
                    <b>{{ t("Choice \$\$index\$\$", ["index" => $i + 1]) }}:</b>
                    @if($choice instanceof Choice)
                        <a class="text-primary hover:text-primary-effect underline transition-all"
                           href="{{ Router::generate("courses-edit", ["course" => $choice->getCourseId()]) }}">
                            {{ $choice->getCourse()->getTitle() }}
                        </a>
                    @else
                        -
                    @endif
                </span>
            @endforeach
        </div>
    @endif

    @include("components.modals.defaultabort")
    <script type="module">
        import * as UsersEdit from "{{ Router::staticFilePath("js/users/edit.js") }}";
        UsersEdit.init();
    </script>
@endcomponent
