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
    case 'etudiants':
        $contenue = "Gestion des etudiants";
        $data = handleStudentAttachedData($idAttache);
        extract($data);
        $classes = $data["classes"];
        $etudiants = $data["students"]["data"];
        $pagination =  $data["students"]["pagination"];
        break;
    case 'justifications':
        $contenue = "Traitement des justifications";
        $data = handleJustificationGetData($idAttache);
        handleJustificationRequests();
        extract($data);
        $justifications = $data["justifications"]["data"];
        $paginations = $data["justifications"]["pagination"];
        break;
    case 'inscriptions':
        $contenue = "Gestion des inscriptions";
        $data = handleInscriptionData($idAttache);
        extract($data);
        $pagination = $etudiants["pagination"];
        $etudiants = $etudiants["data"];
        break;
    default:
        redirectURL("notFound", "error");
        break;
}

ob_start();
require_once ROOT_PATH . PATH_VIEW_ATTACHE . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/attacher.layout.php";
