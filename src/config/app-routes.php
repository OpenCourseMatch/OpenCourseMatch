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
Router::addRoute("POST", "/groups/table", "groups/overview-table.php", "groups-overview-table");
Router::addRoute("GET", "/groups/edit", "groups/edit.php", "groups-create");
Router::addRoute("GET", "/groups/edit/{i:group}", "groups/edit.php", "groups-edit");
Router::addRoute("POST", "/groups/save", "groups/save.php", "groups-save");
Router::addRoute("GET", "/groups/delete/{i:group}", "groups/delete.php", "groups-delete");

// User management
Router::addRoute("GET", "/users", "users/overview.php", "users-overview");
Router::addRoute("POST", "/users/table", "users/overview-table.php", "users-overview-table");
Router::addRoute("GET", "/users/edit", "users/edit.php", "users-create");
Router::addRoute("GET", "/users/edit/{i:user}", "users/edit.php", "users-edit");
Router::addRoute("POST", "/users/save", "users/save.php", "users-save");
Router::addRoute("GET", "/users/delete/{i:user}", "users/delete.php", "users-delete");

// Facilitator management
Router::addRoute("GET", "/facilitators", "facilitators/overview.php", "facilitators-overview");
Router::addRoute("POST", "/facilitators/table", "facilitators/overview-table.php", "facilitators-overview-table");
Router::addRoute("GET", "/facilitators/edit", "facilitators/edit.php", "facilitators-create");
Router::addRoute("GET", "/facilitators/edit/{i:user}", "facilitators/edit.php", "facilitators-edit");
Router::addRoute("POST", "/facilitators/save", "facilitators/save.php", "facilitators-save");
Router::addRoute("GET", "/facilitators/delete/{i:user}", "facilitators/delete.php", "facilitators-delete");

// Administrator management
Router::addRoute("GET", "/administrators", "administrators/overview.php", "administrators-overview");
Router::addRoute("POST", "/administrators/table", "administrators/overview-table.php", "administrators-overview-table");
Router::addRoute("GET", "/administrators/edit", "administrators/edit.php", "administrators-create");
Router::addRoute("GET", "/administrators/edit/{i:user}", "administrators/edit.php", "administrators-edit");
Router::addRoute("POST", "/administrators/save", "administrators/save.php", "administrators-save");
Router::addRoute("GET", "/administrators/delete/{i:user}", "administrators/delete.php", "administrators-delete");

// Course management
Router::addRoute("GET", "/courses", "courses/overview.php", "courses-overview");
Router::addRoute("POST", "/courses/table", "courses/overview-table.php", "courses-overview-table");
Router::addRoute("GET", "/courses/edit", "courses/edit.php", "courses-create");
Router::addRoute("GET", "/courses/edit/{i:course}", "courses/edit.php", "courses-edit");
Router::addRoute("POST", "/courses/save", "courses/save.php", "courses-save");
Router::addRoute("GET", "/courses/delete/{i:course}", "courses/delete.php", "courses-delete");

// System settings
Router::addRoute("GET", "/system-settings", "system-settings/system-settings.php", "system-settings");

// Authentication
Router::addRoute("GET", "/auth/login", "auth/login.php", "auth-login");
Router::addRoute("POST", "/auth/login", "auth/login-action.php", "auth-login-action");
Router::addRoute("GET", "/auth/logout", "auth/logout.php", "auth-logout");
