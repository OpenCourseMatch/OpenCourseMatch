<?php

$user = Auth->enforceLogin(PermissionLevel::FACILITATOR->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false, [], t("The course that should be edited does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("courses-overview"));
}

$course = $get["course"];

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Courses"),
        "link" => Router->generate("courses-overview")
    ],
    [
        "name" => isset($course) ? t("Edit course \$\$name\$\$", ["name" => $course->getTitle()]) : t("Create course"),
        "link" => Router->generate(isset($course) ? "courses-edit" : "courses-create", isset($course) ? ["courseId" => $course->getId()] : [])
    ]
];

echo Blade->run("courses.edit", [
    "breadcrumbs" => $breadcrumbs,
    "course" => $course ?? null
]);
