<?php

define("ROOT_PATH", dirname(__DIR__));
define("ROOT_URL", "http://afrocode.ecole221.sn:8000/");
require_once ROOT_PATH . "/boostrap/require.php";

$controller = isset($_GET["controllers"]) ? $_GET["controllers"] : "security";
handleController($controller);
