<?php

Auth->sessionLogout();
Router->redirect(Router->generate("index"));
