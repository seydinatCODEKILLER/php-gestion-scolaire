<?php

require_once ROOT_PATH . "/models/etudiant.model.php";
require_once ROOT_PATH . "/models/semestre.model.php";
require_once ROOT_PATH . "/models/annee_scolaire.model.php";



function initController()
{
    isUserLoggedIn();
    clearFieldErrors();

    return [
        'page' => $_GET['page'] ?? 'dashboard',
        'role' => getDataFromSession("user", "libelle"),
        'userId' => getDataFromSession("user", "id_utilisateur"),
        'idEtudiant' => getIdEtudiantByIdUtilisateur(getDataFromSession("user", "id_utilisateur")),
        'controllers' => $_GET['controllers'],
        'contenue' => '',
        'message' => getSuccess(),
        'errors' => getFieldErrors()
    ];
}

function getDashboardStateForStudent($id_etudiant): array
{
    return [
        'cours_suivis' => getNombreCoursEtudiant($id_etudiant),
        'absences' => getNombreAbsencesEtudiant($id_etudiant),
        'justifications_soumises' => getNombreJustificationsSoumises($id_etudiant),
        'emploi_du_temps' => getEmploiDuTempsJournalier($id_etudiant)
    ];
}

function handleRequestCourse($id_etudiant,): array
{
    $currentPage = max(1, $_GET['p'] ?? 1);
    $data = [
        'filtered' => [
            'state' => $_GET["state"] ?? "planifié",
            'date_debut' => $_GET["date_debut"] ?? "",
            'date_fin' => $_GET["date_fin"] ?? "",
            'semestre' => $_GET["semestre"] ?? null,
        ],
        'semestres' => getAllSemestres(),
        'active_annees_scolaires' => getActiveAnneeScolaire()
    ];
    $data['cours'] = getCoursEtudiantAnneeEnCours($id_etudiant, $data["filtered"], $currentPage);
    return $data;
}
