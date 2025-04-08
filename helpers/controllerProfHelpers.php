<?php

require_once ROOT_PATH . "/models/cours.model.php";
require_once ROOT_PATH . "/models/module.model.php";
require_once ROOT_PATH . "/models/professeur.model.php";
require_once ROOT_PATH . "/models/absence.model.php";

function initController(): array
{
    return [
        'page' => $_GET['page'] ?? 'dashboard',
        'controllers' => $_GET["controllers"],
        'userId' => getDataFromSession("user", "id_utilisateur"),
        'idProf' => getIdProfesseurByIdUtilisateur(getDataFromSession("user", "id_utilisateur")),
        'role' => getDataFromSession("user", "libelle"),
        'contenue' => '',
        'message' => getSuccess(),
        'errors' => getFieldErrors()
    ];
}

function handleCoursRequests(int $profId): array
{
    $data = [
        'filtered' => [
            'statut' => $_GET['statut'] ?? 'planifié',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? '',
            'id_classe' => $_GET['id_classe'] ?? ''
        ],
        'cours' => [],
        'pagination' => [],
        'classes' => getClassesByProfesseur($profId)
    ];

    if (isset($_GET['details_cours_id'])) {
        $data['details'] = getCoursDetails($_GET['details_cours_id']);
    }

    $result = getCoursByProfesseurs(
        $profId,
        $data['filtered'],
        $_GET['p'] ?? 1,
        10
    );

    $data['cours'] = $result['data'];
    $data['pagination'] = $result['pagination'];

    return $data;
}

function handleAbsencesRequests(int $profId): array
{
    $data = [
        'filtered' => [
            'module' => $_GET['module'] ?? '',
            'date' => $_GET['date'] ?? ''
        ],
        'coursDuJour' => [],
        'coursDeLaSemaine' => [],
        'modules' => getAllModules(),
        'etudiantsCours' => [],
    ];

    // Récupérer les cours du jour
    $data['coursDuJour'] = getCoursDuJourParProfesseur($profId);

    // Récupérer les cours de la semaine
    $data['coursDeLaSemaine'] = getCoursDeLaSemaineParProfesseur($profId);

    // Marquage des absences
    if (isset($_GET['marquer_absences'])) {
        $data['coursDetails'] = getCoursDetails($_GET['marquer_absences']);
        $data['etudiantsCours'] = getEtudiantsPourAbsences($_GET['marquer_absences']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleAbsencesSubmission($_GET['marquer_absences'], $_POST['absents'] ?? []);
        }
    }

    return $data;
}

function isMarquageDisponible(string $dateCours, string $heureDebut): bool
{
    $datetimeCours = new DateTime("$dateCours $heureDebut");
    $now = new DateTime();
    $diff = $now->diff($datetimeCours);
    $hoursDiff = ($now > $datetimeCours) ? $diff->h + ($diff->days * 24) : - ($diff->h + $diff->days * 24);

    return $hoursDiff <= 24 && $hoursDiff >= 0;
}


function handleAbsencesSubmission(int $coursId, array $absentsIds): void
{
    if (enregistrerAbsences($coursId, $absentsIds)) {
        setSuccess("Absences enregistrées avec succès");
    } else {
        setFieldError('general', "Erreur lors de l'enregistrement");
    }
    redirectURL('professeur', 'absences');
}

function getDashboardData(int $profId): array
{
    return [
        'stats' => getProfesseurStats($profId),
        'absences_par_module' => getAbsencesByModule($profId),
        'absencesRecent' => getRecentAbsences($profId, 5),
        'top_absents' => getTopAbsentStudents($profId, 5),
    ];
}
