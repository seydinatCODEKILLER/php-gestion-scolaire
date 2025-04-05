<?php
isUserLoggedIn();
require_once ROOT_PATH . "/models/professeur.model.php";
require_once ROOT_PATH . "/helpers/controllerProfHelpers.php";

define("PATH_VIEW_RP", "/views/pages/professeurs/");
$controller = initController();
extract($controller);

switch ($page) {
    case 'dashboard':
        $contenue = "Dashboard";
        $data = [
            'stats' => getProfesseurStats($userId),
            'absences_par_module' => getAbsencesByModule($userId),
            'top_absents' => getTopAbsentStudents($userId),
            'cours_par_module' => getCoursByModule($userId)
        ];
        break;
    default:
        redirectURL("notFound", "error");
        break;
}
ob_start();
require_once ROOT_PATH . PATH_VIEW_RP . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
