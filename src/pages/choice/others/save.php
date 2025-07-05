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

Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating the course choices for user {$account->getId()} ({$account->getFullName()}).");

// Delete old choices from database to prevent collisions
Logger::getLogger("Choices")->trace("Deleting all old choices for user {$account->getId()} ({$account->getFullName()})");
$oldChoices = Choice::dao()->getObjects([
    "userId" => $account->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

$recreateOldChoices = function() use ($oldChoices, $account) {
    Logger::getLogger("Choices")->trace("Recreating old choices for user {$account->getId()} ({$account->getFullName()}).");
    foreach($oldChoices as $oldChoice) {
        Choice::dao()->save($oldChoice);
    }
};

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getName()}) for user {$account->getId()} ({$account->getFullName()}) multiple times.");
        $recreateOldChoices();
        new InfoMessage(t("Each course can only be chosen once."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("choice-edit-others", ["user" => $account->getId()]));
    }

    if(!$course->canChooseCourse($account)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getName()}) for user {$account->getId()} ({$account->getFullName()}) but they do not meet the requirements.");
        $recreateOldChoices();
        new InfoMessage(t("The user of which the choice should be edited does not meet the requirements to participate in at least one of the chosen courses."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("choice-edit-others", ["user" => $account->getId()]));
    }

    Logger::getLogger("Choices")->trace("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is choosing course {$course->getId()} ({$course->getName()}) for user {$account->getId()} ({$account->getFullName()}) with priority {$i}.");

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($account->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Save new choices to database
Logger::getLogger("Choices")->trace("Saving new choices for user {$account->getId()} ({$account->getFullName()}).");
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}
Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated the course choices for user {$account->getId()} ({$account->getFullName()}).");

new InfoMessage(t("The user's chosen courses have been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("users-edit", ["user" => $account->getId()]));
