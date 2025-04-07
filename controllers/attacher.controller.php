<?php
isUserLoggedIn();
require_once ROOT_PATH . "/helpers/controllerAttHelpers.php";


define("PATH_VIEW_ATTACHE", "/views/pages/attacher/");
$controller = initController();
extract($controller);

switch ($page) {
    case 'dashboard':
        $contenue = "Tableau de bord";
        $data = getDashboardDataAttacher($idAttache);
        extract($data);
        break;
    case 'classes':
        $contenue = "Gestion des classes";
        $data = handleClassesRequests($idAttache);
        extract($data);
        break;
    case 'absences':
        $contenue = "Gestion des absences";
        break;
    case 'justifications':
        $contenue = "Traitement des justifications";
        $data = handleJustificationGetData($idAttache);
        handleJustificationRequests();
        extract($data);
        $justifications = $data["justifications"]["data"];
        $paginations = $data["justifications"]["pagination"];
        break;
    default:
        redirectURL("notFound", "error");
        break;
}

ob_start();
require_once ROOT_PATH . PATH_VIEW_ATTACHE . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
