<?php

Router::addRoute("GET", "/", "index.php", "index");
Router::addRoute("GET|POST", "/404", "404.php", "404");
Router::addRoute("GET|POST", "/400", "400.php", "400");

Router::addRoute("POST", "/translations-api", "translations/api.php", "translations-api");

// Dashboard
Router::addRoute("GET", "/dashboard", "dashboard.php", "dashboard");

// Account settings
Router::addRoute("GET", "/account-settings", "account-settings/account-settings.php", "account-settings");
Router::addRoute("GET", "/account-settings/change-password", "account-settings/change-password.php", "account-settings-change-password");
Router::addRoute("POST", "/account-settings/change-password", "account-settings/change-password-action.php", "account-settings-change-password-action");

// Group management
Router::addRoute("GET", "/groups", "groups/overview.php", "groups-overview");
Router::addRoute("POST", "/groups-table", "groups/overview-table.php", "groups-overview-table");
Router::addRoute("GET", "/groups/edit", "groups/edit.php", "groups-create");
Router::addRoute("GET", "/groups/edit/{i:groupId}", "groups/edit.php", "groups-edit");
Router::addRoute("POST", "/groups/save", "groups/save.php", "groups-save");
Router::addRoute("GET", "/groups/delete/{i:groupId}", "groups/delete.php", "groups-delete");

// Authentication
Router::addRoute("GET", "/auth/login", "auth/login.php", "auth-login");
Router::addRoute("POST", "/auth/login", "auth/login-action.php", "auth-login-action");
Router::addRoute("GET", "/auth/logout", "auth/logout.php", "auth-logout");
