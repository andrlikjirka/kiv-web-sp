<?php

use kivweb_sp\ApplicationStart;

// nactu autoloader
require_once("classAutoloader.inc.php");

// nactu vlastni nastaveni webu
require_once("app/config/settings.inc.php");

//spusteni aplikace
$app = new ApplicationStart();
$app->appStart();