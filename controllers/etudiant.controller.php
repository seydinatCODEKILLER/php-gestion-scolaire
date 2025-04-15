<?php
isUserLoggedIn();
require_once ROOT_PATH . "/helpers/controllerEtuHelpers.php";


define("PATH_VIEW_ETUDIANT", "/views/pages/etudiant/");
$controller = initController();
extract($controller);

switch ($page) {
    case 'dashboard':
        $contenue = "Tableau de bord";
        $data = getDashboardStateForStudent($idEtudiant);
        break;
    case 'cours':
        $contenue = "Gestion des cours";
        $data = handleRequestCourse($idEtudiant);
        extract($data);
        $coursSuivit = $cours["data"];
        $pagination = $cours["pagination"];
        break;
    case 'justifications':
        $contenue = "Gestion des justifications";
        $data = handleJustificationRequest($idEtudiant);
        extract($data);
        $justificationsToShow = $justifications['data'];
        // dumpDie($data);
        $pagination = $justifications["pagination"];
        break;
    case 'absences':
        $contenue = "Gestion des absences";
        $data = handleRequestAbsences($idEtudiant);
        extract($data);
        break;
    default:
        redirectURL("notFound", "error");
        break;
}

ob_start();
require_once ROOT_PATH . PATH_VIEW_ETUDIANT . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/etudiant.layout.php";
