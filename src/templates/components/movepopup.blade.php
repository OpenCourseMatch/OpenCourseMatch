<div>
    @if($leadingCourse !== null)
        <h3>
            {{ t("Leading course") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @include("components.movecourse", [
                "course" => $leadingCourse,
                "errors" => $errors,
                "highlighting" => $highlighting
            ])
        </div>
    @endif

    @if(!empty($chosenCourses))
        <h3>
            {{ t("Chosen courses") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($chosenCourses as $course)
                @include("components.movecourse", [
                    "course" => $course,
                    "errors" => $errors,
                    "highlighting" => $highlighting
                ])
            @endforeach
        </div>
    @endif

    @if(!empty($otherCourses))
        <h3>
            {{ t("Other courses") }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($otherCourses as $course)
                @include("components.movecourse", [
                    "course" => $course,
                    "errors" => $errors,
                    "highlighting" => $highlighting
                ])
            @endforeach
        </div>
    @endif
</div>