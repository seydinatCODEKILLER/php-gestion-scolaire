<?php
isUserLoggedIn();
require_once ROOT_PATH . "/models/responsable.model.php";
require_once ROOT_PATH . "/helpers/controllerRpHelpers.php";

define("PATH_VIEW_RP", "/views/pages/responsable/");
$controller = initController();
extract($controller);

switch ($page) {
    case 'dashboard':
        $contenue = "Dashboard";
        $data = getDashboardStats();
        $coursFiliere =  getCoursByFiliere();
        break;
    case 'classes':
        clearFieldErrors();
        $contenue = "Gérer les classes";
        $crudData = handleCRUD('classe', [
            'niveau' => $_GET['niveau'] ?? '',
            'filiere' => $_GET['filiere'] ?? ''
        ]);
        extract($crudData);
        $currentPage = max(1, $_GET['p'] ?? 1);
        $result = getFilteredClasses($filtered, $currentPage, 3);
        $classes = $result['data'];
        $classeDetails = $details;
        $classeToEdit = $toEdit;
        $pagination = $result['pagination'];
        $filieres = getAllFileres();
        $niveaux = getAllNiveaux();
        $annees = getAllAnneesScolaires();

        break;
    case 'professeurs':
        $contenue = "Gérer les professeurs";
        $crudData = handleCRUD('professeur', [
            'search' => $_GET['search'] ?? ''
        ]);
        extract($crudData);
        $currentPage = max(1, $_GET['p'] ?? 1);
        $result = getAllProfesseurs($filtered, $currentPage, 2);
        $professeurs = $result;
        $classes = getAllClasses();

        break;
    case 'cours':
        $contenue = "Gérer les cours";
        $crudData = handleCRUD('cours', [
            'statut' => $_GET['statut'] ?? 'planifié',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? '',
            'id_classe' => $_GET['id_classe'] ?? ''
        ]);

        extract($crudData);

        $currentPage = max(1, $_GET['p'] ?? 1);
        $result = getAllCours($filtered, $currentPage, 3);
        $cours = $result['data'];
        $pagination = $result['pagination'];

        // Pour les formulaires
        $modules = getAllModules();
        $professeurs = getAllProfesseurs()["data"];
        $classes = getAllClasses();
        $semestres = getAllSemestres();
        $coursToEdit = $toEdit;

        break;
    case 'filieres':
        $contenue = "Gérer les filières";
        $crudData = handleCRUD('filiere');
        extract($crudData);
        $currentPage = max(1, $_GET['p'] ?? 1);
        $filieres = getAllFilieres([], $currentPage, 4);
        $filiereToEdit = $toEdit;

        break;
    case 'niveaus':
        $contenue = "Gérer les niveaux";
        $crudData = handleCRUD('niveau');
        extract($crudData);
        $currentPage = max(1, $_GET['p'] ?? 1);
        $niveaux = getAllNiveau([], $currentPage, 5);
        break;
    default:
        redirectURL("notFound", "error");
        break;
}
ob_start();
require_once ROOT_PATH . PATH_VIEW_RP . ($page === 'dashboard' ? 'dashboard.html.php' : "{$page}.html.php");
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
