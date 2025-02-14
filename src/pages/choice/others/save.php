<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$getValidation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value,
            ])->setErrorMessage(t("The user of which the choice should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $getValidation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-overview"));
}

$account = $get["user"];

$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));

$singleChoiceValidation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsInDatabase::create(Course::dao())
]);

$choiceValidation = [];
for($i = 0; $i < $choiceCount; $i++) {
    $choiceValidation[$i] = $singleChoiceValidation;
}

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "choice" => \validation\Validator::create([
            \validation\IsArray::create(),
            \validation\MinLength::create($choiceCount),
            \validation\MaxLength::create($choiceCount),
            \validation\HasChildren::create($choiceValidation)
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("choice-edit-others", ["user" => $account->getId()]));
}

// Delete old choices from database to prevent collisions
$oldChoices = Choice::dao()->getObjects([
    "userId" => $account->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        new InfoMessage(t("Each course can only be chosen once."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("choice-edit-others", ["user" => $account->getId()]));
    }

    if(!$course->canChooseCourse($account)) {
        new InfoMessage(t("The user of which the choice should be edited does not meet the requirements to participate in at least one of the chosen courses."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("choice-edit-others", ["user" => $account->getId()]));
    }

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($account->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Save new choices to database
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}

new InfoMessage(t("The user's chosen courses have been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("users-edit", ["user" => $account->getId()]));
