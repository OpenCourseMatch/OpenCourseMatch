<h2 class="mt-4 mb-2">
    {{ t("Statistics") }}
</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    @component("ui.box", [
        "scheme" => BoxScheme::SURFACE
    ])
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center justify-center shrink-0 w-10 h-10 bg-primary-500 rounded-full shadow">
                {{-- TODO: Icon visibility on mobile --}}
                @include("icons.status", [
                    "class" => "w-2/3 h-2/3 fill-surface-100"
                ])
            </div>

            <p class="text-xl font-bold">
                {{ t("System status") }}
            </p>
        </div>

        <div class="flex gap-2">
            @if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") === "true")
                @include("icons.checkcircle", [
                    "class" => "fill-safe-500"
                ])
            @else
                @include("icons.crosscircle", [
                    "class" => "fill-danger-500"
                ])
            @endif
            <p>
                {{ t("Course choice allowed") }}
            </p>
        </div>

        <div class="flex gap-2">
            @if(\app\settings\SystemStatus::dao()->get("coursesAssigned") === "true")
                @include("icons.checkcircle", [
                    "class" => "fill-safe-500"
                ])
            @else
                @include("icons.crosscircle", [
                    "class" => "fill-danger-500"
                ])
            @endif
            <p>
                {{ t("Courses assigned") }}
            </p>
        </div>

        <div class="flex gap-2">
            @if(\app\settings\SystemStatus::dao()->get("courseAssignmentPublic") === "true")
                @include("icons.checkcircle", [
                    "class" => "fill-safe-500"
                ])
            @else
                @include("icons.crosscircle", [
                    "class" => "fill-danger-500"
                ])
            @endif
            <p>
                {{ t("Course assignment public") }}
            </p>
        </div>
    @endcomponent
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    @component("ui.box", [
        "scheme" => BoxScheme::SURFACE
    ])
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-4xl font-bold">
                {{ $numberOfParticipantsAndTutors ? number_format($numberOfParticipantsAndTutors, 0, ",", ".") : 0 }}
            </p>
            <p>
                {{ t("Participants and tutors") }}
            </p>
        </div>
    @endcomponent

    @component("ui.box", [
        "scheme" => BoxScheme::SURFACE
    ])
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-4xl font-bold">
                {{ $numberOfCourses ? number_format($numberOfCourses, 0, ",", ".") : 0 }}
            </p>
            <p>
                {{ t("Courses") }}
            </p>
        </div>
    @endcomponent
</div>
