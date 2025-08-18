<?php

Auth->logout();
Router->redirect(Router->generate("index"));
