<?php

require 'Database.php';

use Slim\App;

// connect to the Slim application
return function (App $app) {
    $app->add(new Database());
};
