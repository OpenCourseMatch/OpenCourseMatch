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
</div>