<?php require ("Vendor/autoload.php");

use Engine\Libs\Controller;
use Engine\Libs\Factory;

date_default_timezone_set("Europe/Moscow");

$factory = new Factory();
if(!$factory->isExists()) Controller::sendResponse(Controller::RESULT_ERROR);
if(!$factory->exec()) Controller::sendResponse(Controller::RESULT_ERROR);