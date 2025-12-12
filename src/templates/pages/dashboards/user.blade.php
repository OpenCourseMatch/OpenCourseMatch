<h2 class="mt-4 mb-2">
    {{ t("Choose courses") }}
</h2>
@if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") !== "true")
    @component("shells.generic.infomessage", [
        "type" => \struktal\InfoMessage\InfoMessageType::WARNING
    ])
        {{ t("The course selection has already been disabled. You can no longer update your course preferences.") }}
    @endcomponent
@else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @include("ui.dashboardlink", [
            "icon" => "icons.course",
            "href" => Router->generate("choice-edit"),
            "title" => t("Choose courses"),
            "description" => t("Rank your favourite courses that you would like to participate in."),
            "scheme" => BoxScheme::SURFACE
        ])
    </div>
@endif
