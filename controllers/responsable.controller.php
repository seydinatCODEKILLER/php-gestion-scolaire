<?php
isUserLoggedIn();
define("PATH_VIEW_RP", "/views/pages/responsable/");
$page = isset($_GET["page"]) ? $_GET["page"] : "dashboard";

ob_start();
switch ($page) {
    case 'dashboard':
        require_once ROOT_PATH . PATH_VIEW_RP . "dashboard.html.php";
        break;
    default:
        redirectURL("notFound", "error");
        break;
}
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/security.layout.php";
