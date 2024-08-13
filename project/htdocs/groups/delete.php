<?php

if(isset($_GET["groupId"]) && is_numeric($_GET["groupId"])) {
    $groupId = $_GET["groupId"];
    $group = Group::dao()->getObject(["id" => $groupId]);
    if(!$group instanceof Group) {
        new InfoMessage(t("The group that should be deleted does not exist."), InfoMessageType::ERROR);
        Comm::redirect("groups-overview");
    }

    Group::dao()->delete($group);
    new InfoMessage(t("The group has been deleted."), InfoMessageType::SUCCESS);
    Comm::redirect(Router::generate("groups-overview"));
} else {
    new InfoMessage(t("The group that should be deleted does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("groups-overview"));
}
