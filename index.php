<?php
include "./Utils/DB.php";
include "./Utils/Template.php";
include "./Utils/Constants.php";
include "./App.php";

session_start();

global $app;
$app = new App();
$app->run();
?>