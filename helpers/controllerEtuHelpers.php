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

function handleRequestCourse($id_etudiant): array
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

function handleRequestAbsences($id_etudiant): array
{
    $data = [
        'filtered' => [
            'date_debut' => $_GET["date_debut"] ?? "",
            'date_fin' => $_GET["date_fin"] ?? "",
        ],
    ];
    handleCRUDAbsences($id_etudiant);
    $data["absences"] = getAbsencesNonJustifiees($id_etudiant, $data["filtered"]);
    return $data;
}


function handleCRUDAbsences($id_etudiant)
{
    if (is_request_method("post")) {
        if (validateAbsenceEtudiant($_POST)) {
            $piece_jointe = null;
            if (isset($_FILES["piece_jointe"]["name"])) {
                $piece_jointe = uploadAvatar($_FILES["piece_jointe"], "jointures", "jr_");
            }
            $id_absence =  $_POST["id_absence"] ?? null;
            $motif = $_POST["motif"];
            $data = [
                "id_absence" => $id_absence,
                "id_etudiant" => $id_etudiant,
                "motif" => $motif,
                "piece_jointe" => $piece_jointe
            ];
            $justificationsId = enregistrerJustification($id_absence, $id_etudiant, $motif, $piece_jointe);
            if ($justificationsId) {
                setSuccess("Jstification envoyer avec succès");
                redirectURL("etudiant", "absences");
            } else {
                setFieldError("general", "Une erreur est survenue lors de l'inscription");
                if ($piece_jointe && file_exists(ROOT_PATH . "/public/uploads/jointures/$piece_jointe")) {
                    unlink(ROOT_PATH . "/public/uploads/jointures/$piece_jointe");
                }
            }
        }
    }
}
