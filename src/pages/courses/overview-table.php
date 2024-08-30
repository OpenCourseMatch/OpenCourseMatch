<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$courses = Course::dao()->getObjects();

$courses = array_map(function($course) {
    $array = $course->toArray();
    $array["editHref"] = Router::generate("courses-edit", ["courseId" => $course->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $courses);

Comm::sendJson($courses);
