<?php

if(isset($_POST["groupId"]) && is_numeric($_POST["groupId"])) {
    $groupId = $_POST["groupId"];
    $group = Group::dao()->getObject(["id" => $groupId]);
    if(!$group instanceof Group) {
        new InfoMessage(t("The group that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect("groups-overview");
    }
} else {
    $group = new Group();
}

if(empty($_POST["name"]) || empty($_POST["clearance"]) || !is_numeric($_POST["clearance"])) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    Comm::redirect("groups-edit");
}

$group->setName($_POST["name"]);
$group->setClearance(intval($_POST["clearance"]));
Group::dao()->save($group);

new InfoMessage(t("The group has been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("groups-overview"));
