<?php

require_once ROOT_PATH . "/models/etudiant.model.php";


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
