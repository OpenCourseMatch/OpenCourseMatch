<?php

$user = Auth->enforceLogin(PermissionLevel::USER->value, Router->generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    new InfoMessage(t("Choosing courses is only available to participants and tutors."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("index"));
}

if(SystemStatus::dao()->get("userActionsAllowed") !== "true") {
    new InfoMessage(t("The course selection has already been disabled. You can no longer update your course preferences."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("index"));
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
    Router->redirect(Router->generate("choice-edit"));
}

Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating their course choices.");

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) multiple times.");
        new InfoMessage(t("Each course can only be chosen once."), InfoMessageType::ERROR);
        Router->redirect(Router->generate("choice-edit"));
    }

    if(!$course->canChooseCourse($user)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) but does not meet the requirements.");
        new InfoMessage(t("You do not meet the requirements to participate in at least one of your chosen courses."), InfoMessageType::ERROR);
        Router->redirect(Router->generate("choice-edit"));
    }

    Logger::getLogger("Choices")->trace("User {$user->getId()} ({$user->getFullName()}) is choosing course {$course->getId()} ({$course->getTitle()}) with priority {$i}.");

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($user->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Delete old choices from database to prevent collisions
Logger::getLogger("Choices")->trace("Deleting all old choices for user {$user->getId()} ({$user->getFullName()})");
$oldChoices = Choice::dao()->getObjects([
    "userId" => $user->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

// Save new choices to database
Logger::getLogger("Choices")->trace("Saving new choices for user {$user->getId()} ({$user->getFullName()}).");
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}
Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated their course choices.");

new InfoMessage(t("Your chosen courses have been saved."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("dashboard"));
