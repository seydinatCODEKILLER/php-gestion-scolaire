<?php
isUserLoggedIn();
require_once ROOT_PATH . "/helpers/controllerProfHelpers.php";

define("PATH_VIEW_PROF", "/views/pages/professeurs/");
$controller = initController();
extract($controller);

switch ($page) {
    case 'dashboard':
        $contenue = "Tableau de bord";
        $data = getDashboardData($idProf);
        extract($data);
        break;

    case 'cours':
        $contenue = "Mes cours";
        $crudData = handleCoursRequests($idProf);
        extract($crudData);
        break;

    case 'absences':
        $contenue = "Gestion des absences";
        $crudData = handleAbsencesRequests($idProf);
        extract($crudData);
        break;

    default:
        redirectURL("notFound", "error");
        break;
}

ob_start();
require_once ROOT_PATH . PATH_VIEW_PROF . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
