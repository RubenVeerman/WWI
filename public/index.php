<?php

// var_dump(in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
// var_dump($_SERVER['REMOTE_ADDR']);
// die();
session_start();
require_once "../boot/autoloader.php";

use App\Core\StartUp;

$autoloader = new AutoLoader();
$autoloader->addDirectories( [ "../" ] );
$autoloader->register();

echo (new StartUp())->loadSiteData();
?>