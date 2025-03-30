<?php

$page = isset($_GET["page"]) ? $_GET["page"] : "error";

ob_start();

switch ($page) {
    case 'error':
        require_once ROOT_PATH . "/views/error/view.error.php";
        break;
}

$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/error.layout.php";
