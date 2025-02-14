<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    new InfoMessage(t("Choosing courses is only available to participants and tutors."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

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
    Comm::redirect(Router::generate("choice-edit"));
}

// Delete old choices from database to prevent collisions
$oldChoices = Choice::dao()->getObjects([
    "userId" => $user->getId()
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
        Comm::redirect(Router::generate("choice-edit"));
    }

    if(!$course->canChooseCourse($user)) {
        new InfoMessage(t("You do not meet the requirements to participate in at least one of your chosen courses."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("choice-edit"));
    }

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($user->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Save new choices to database
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}

new InfoMessage(t("Your chosen courses have been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("dashboard"));
