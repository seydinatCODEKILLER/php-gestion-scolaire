<?php
isUserLoggedIn();
require_once ROOT_PATH . "/models/responsable.model.php";
define("PATH_VIEW_RP", "/views/pages/responsable/");
$page = isset($_GET["page"]) ? $_GET["page"] : "dashboard";
$role = getDataFromSession("user", "libelle");

ob_start();
switch ($page) {
    case 'dashboard':
        $data = getDashboardStats();
        $coursFiliere =  getCoursByFiliere();
        require_once ROOT_PATH . PATH_VIEW_RP . "dashboard.html.php";
        break;
    case 'classes':
        require_once ROOT_PATH . PATH_VIEW_RP . "classes.html.php";
        break;
    case 'professeurs':
        require_once ROOT_PATH . PATH_VIEW_RP . "professeurs.html.php";
        break;
    case 'cours':
        require_once ROOT_PATH . PATH_VIEW_RP . "cours.html.php";
        break;
    default:
        redirectURL("notFound", "error");
        break;
}
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
