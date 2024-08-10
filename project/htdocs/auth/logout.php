<?php

Auth::logout();
Comm::redirect(Router::generate("index"));
