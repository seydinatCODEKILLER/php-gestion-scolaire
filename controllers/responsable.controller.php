<?php
isUserLoggedIn();
require_once ROOT_PATH . "/models/responsable.model.php";
define("PATH_VIEW_RP", "/views/pages/responsable/");
$page = isset($_GET["page"]) ? $_GET["page"] : "dashboard";
$role = getDataFromSession("user", "libelle");

ob_start();
switch ($page) {
    case 'dashboard':
        $contenue = "Dashboard";
        $data = getDashboardStats();
        $coursFiliere =  getCoursByFiliere();
        require_once ROOT_PATH . PATH_VIEW_RP . "dashboard.html.php";
        break;
    case 'classes':
        clearFieldErrors();
        $contenue = "Gerer l'integralite de vos classe";
        $classeToEdit = null;
        $classeDetails = null;
        $formData = [];

        // Traitement GET
        if (is_request_method("get")) {
            // Filtres principaux
            $filtered = [
                "niveau" => $_GET["niveau"] ?? "",
                "filiere" => $_GET["filiere"] ?? "",
                "state" => $_GET["state"] ?? "disponible"
            ];

            // Détails de la classe
            if (isset($_GET['details_classe_id'])) {
                $classeDetails = getClasseDetails($_GET['details_classe_id']);
                if (!$classeDetails) {
                    setFieldError("general", "Classe introuvable");
                }
            }

            // Edition de classe
            if (isset($_GET['edit_classe_id'])) {
                $classeToEdit = getClasseById($_GET['edit_classe_id']);
                if (!$classeToEdit) {
                    redirectURL("notFound", "error");
                }
            }

            // Archivage/Désarchivage
            $archiveActions = [
                'archived_classe_id' => 'archiver',
                'unarchive_classe_id' => 'disponible'
            ];

            foreach ($archiveActions as $param => $status) {
                if (isset($_GET[$param])) {
                    if (toggleClassStatus($_GET[$param], $status)) {
                        setSuccess("Classe " . ($status === 'archiver' ? "archivée" : "désarchivée") . " avec succès");
                    }
                    redirectURL("responsable", "classes");
                }
            }
        }

        // Traitement POST (ajout/modification)
        if (is_request_method("post")) {
            $formData = [
                "id_classe" => (int)($_POST["id_classe"] ?? 0),
                "libelle" => trim($_POST["libelle"] ?? ""),
                "filiere" => trim($_POST["filiere"] ?? ""),
                "niveau" => trim($_POST["niveau"] ?? ""),
                "capacite" => trim($_POST["capacite"] ?? ""),
                "annee_scolaire" => trim($_POST['annee_scolaire'] ?? "")
            ];

            // Validation
            $isValid = validateDataAddClasse($formData);

            // Vérification unicité pour les nouvelles classes
            if ($isValid && $formData['id_classe'] === 0) {
                $existingClass = findClasseByLibelle($formData['libelle']);
                if ($existingClass) {
                    setFieldError('libelle', "Cette classe existe déjà");
                    $isValid = false;
                }
            }

            // Enregistrement
            if ($isValid) {
                $success = $formData['id_classe'] > 0
                    ? updateClasse($formData)
                    : createClasse($formData);

                if ($success) {
                    setSuccess($formData['id_classe'] > 0
                        ? "Classe modifiée avec succès"
                        : "Classe ajoutée avec succès");
                    redirectURL("responsable", "classes");
                } else {
                    setFieldError("general", "Erreur lors de l'enregistrement");
                }
            }
        }

        // Préparation des données pour la vue
        $message = getSuccess();
        clearSuccess();
        $currentPage = max(1, $_GET['p'] ?? 1);
        $perPage = 3;
        $result = getFilteredClasses($filtered, $currentPage, $perPage);
        $classes = $result['data'];
        $pagination = $result['pagination'];
        $filieres = getAllFileres();
        $niveaux = getAllNiveaux();

        require_once ROOT_PATH . PATH_VIEW_RP . "classes.html.php";
        break;
    case 'professeurs':
        require_once ROOT_PATH . PATH_VIEW_RP . "professeurs.html.php";
        break;
    case 'cours':
        require_once ROOT_PATH . PATH_VIEW_RP . "cours.html.php";
        break;
    case 'filieres':
        $contenue = "Gérer les filières de l'école";
        clearFieldErrors();
        $filiereToEdit = null;
        $formData = [];
        if (is_request_method("get")) {
            if (isset($_GET['edit_filiere_id'])) {
                $filiereToEdit = getFiliereById($_GET['edit_filiere_id']);
                if (!$filiereToEdit) {
                    redirectURL("notFound", "error");
                }
            }

            $archiveActions = [
                'archived_filiere_id' => 'archiver',
                'unarchive_filiere_id' => 'disponible'
            ];

            foreach ($archiveActions as $param => $status) {
                if (isset($_GET[$param])) {
                    if (toggleFiliereStatus($_GET[$param], $status)) {
                        setSuccess("Filieres " . ($status === 'archiver' ? "archivée" : "désarchivée") . " avec succès");
                    }
                    redirectURL("responsable", "filieres");
                }
            }
        }
        if (is_request_method("post")) {
            $formData = [
                "id_filiere" => (int)($_POST["id_filiere"] ?? 0),
                "libelle" => trim($_POST["libelle"] ?? ""),
                "description" => trim($_POST["description"] ?? "")
            ];

            $isValid = validateDataAddFiliere($formData);

            if ($isValid && $formData['id_filiere'] === 0) {
                $existing = findFiliereByLibelle($formData['libelle']);
                if ($existing) {
                    setFieldError('libelle', "Cette filière existe déjà");
                    $isValid = false;
                }
            }

            if ($isValid) {
                $success = $formData['id_filiere'] > 0
                    ? updateFiliere($formData)
                    : createFiliere($formData);

                if ($success) {
                    setSuccess($formData['id_filiere'] > 0
                        ? "Filière modifiée avec succès"
                        : "Filière ajoutée avec succès");
                    redirectURL("responsable", "filieres");
                } else {
                    setFieldError("general", "Erreur lors de l'enregistrement");
                }
            }
        }
        $message = getSuccess();
        clearSuccess();

        $currentPage = max(1, $_GET['p'] ?? 1);
        $perPage = 4;
        $filieres = getAllFilieres([], $currentPage, $perPage);
        require_once ROOT_PATH . PATH_VIEW_RP . "filieres.html.php";
        break;
    case 'niveaux':
        require_once ROOT_PATH . PATH_VIEW_RP . "niveaux.html.php";
        break;
    default:
        redirectURL("notFound", "error");
        break;
}
$content = ob_get_clean();
require_once ROOT_PATH . "/views/layout/public.layout.php";
