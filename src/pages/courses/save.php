<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "course" => \validation\Validator::create([
            \validation\IsInDatabase::create(Course::dao())->setErrorMessage(t("The course that should be edited does not exist."))
        ]),
        "title" => \validation\Validator::create([
            \validation\IsRequired::create(true),
            \validation\IsString::create(),
            \validation\MaxLength::create(256),
        ]),
        "organizer" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsString::create(),
            \validation\MaxLength::create(256)
        ]),
        "minClearance" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInteger::create()
        ]),
        "maxClearance" => \validation\Validator::create([
            \validation\NullOnEmpty::create(),
            \validation\IsInteger::create()
        ]),
        "maxParticipants" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInteger::create(),
            \validation\MinValue::create(1)
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    if(isset($_POST["course"]) && !Course::dao()->hasId($_POST["course"])) {
        Comm::redirect(Router::generate("courses-overview"));
    } else if(isset($_POST["course"])) {
        Comm::redirect(Router::generate("courses-edit", ["course" => $_POST["course"]]));
    } else {
        Comm::redirect(Router::generate("courses-create"));
    }
}

if(isset($post["maxClearance"]) && $post["minClearance"] > $post["maxClearance"]) {
    new InfoMessage(t("The minimum clearance level must be lower than the maximum clearance level."), InfoMessageType::ERROR);
    if(isset($post["course"])) {
        Comm::redirect(Router::generate("courses-edit", ["course" => $post["course"]->getId()]));
    } else {
        Comm::redirect(Router::generate("courses-create"));
    }
}

$course = new Course();
if(isset($post["course"])) {
    $course = $post["course"];
}

$course->setTitle($post["title"]);
$course->setOrganizer($post["organizer"]);
$course->setMinClearance($post["minClearance"]);
$course->setMaxClearance($post["maxClearance"]);
$course->setMaxParticipants($post["maxParticipants"]);
Course::dao()->save($course);

new InfoMessage(t("The course has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("courses-overview"));
