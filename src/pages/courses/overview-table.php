<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$courses = \app\courses\Course::dao()->getObjects();

$courses = array_map(function(\app\courses\Course $course) {
    $array = $course->toArray();
    $array["href"] = Router->generate("courses-edit", ["course" => $course->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $courses);

\struktal\API\API::sendJson($courses);
