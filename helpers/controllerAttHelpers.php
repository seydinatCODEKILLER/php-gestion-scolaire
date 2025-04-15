<?php

require_once ROOT_PATH . "/models/attacher.model.php";
require_once ROOT_PATH . "/models/justification.model.php";

function initController()
{
    isUserLoggedIn();
    clearFieldErrors();

    return [
        'page' => $_GET['page'] ?? 'dashboard',
        'role' => getDataFromSession("user", "libelle"),
        'userId' => getDataFromSession("user", "id_utilisateur"),
        'idAttache' => getIdAttacheByIdUtilisateur(getDataFromSession("user", "id_utilisateur")),
        'controllers' => $_GET['controllers'],
        'contenue' => '',
        'message' => getSuccess(),
        'errors' => getFieldErrors()
    ];
}

function handleClassesRequests(int $idAttache): array
{
    $data = [
        'filtered' => [
            'search' => $_GET['search'] ?? ''
        ],
        'details' => null
    ];
    if (isset($_GET['details_classe_id'])) {
        $data['details'] = getClasseDetailsAttacher($_GET['details_classe_id']);
    }
    $data['classes'] = getClassesWithStats($idAttache, $data['filtered']);
    return $data;
}

function handleJustificationGetData(int $idAttache)
{
    $data = [
        'filtered' => [
            'date_debut' => !empty($_GET['date_debut']) ? (new DateTime($_GET['date_debut']))->format('Y/m/d') : '',
            'date_fin' => !empty($_GET['date_fin']) ? (new DateTime($_GET['date_fin']))->format('Y/m/d') : '',
            'statut' => $_GET["statut"] ?? ''
        ],
    ];
    $data['justifications'] = getJustificationsByAttache($idAttache, $data["filtered"]);
    return $data;
}

function getClasseDetailsAttacher(int $idClasse): ?array
{

    return [
        'info' => fetchResult(
            "SELECT c.*, f.libelle as filiere 
            FROM classes c
            JOIN filieres f ON c.id_filiere = f.id_filiere
            WHERE c.id_classe = ?",
            [$idClasse],
            false
        ),
        'etudiants' => fetchResult(
            "SELECT e.*, u.nom, u.prenom, u.email, u.telephone
            FROM etudiants e
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN inscriptions i ON e.id_etudiant = i.id_etudiant
            WHERE i.id_classe = ? AND u.state = 'disponible'
            ORDER BY u.nom, u.prenom",
            [$idClasse]
        )
    ];
}

function getDashboardDataAttacher(int $idAttache): array
{
    return [
        'nb_classes' => countClassesByAttache($idAttache) ?? 0,
        'nb_etudiants' => countEtudiantsByAttache($idAttache) ?? 0,
        'justifications_en_attente' => countJustificationsEnAttenteByAttache($idAttache) ?? 0,
        'recentAbsences' => getDernieresAbsencesByAttache($idAttache)
    ];
}

function handleJustificationRequests(): void
{
    $actions = [
        'justification_accepted_id' => [
            'statut' => 'acceptée',
            'success_message' => 'Justification validée avec succès',
            'error_message' => 'Échec de la validation de la justification'
        ],
        'justification_denied_id' => [
            'statut' => 'refusée',
            'success_message' => 'Justification refusée avec succès',
            'error_message' => 'Échec du refus de la justification'
        ],
        'cancel_justification_id' => [
            'statut' => 'en attente',
            'success_message' => 'Justification remise en attente',
            'error_message' => 'Échec de l\'annulation de la justification'
        ]
    ];

    foreach ($actions as $param => $config) {
        if (isset($_GET[$param])) {
            $idJustification = $_GET[$param];
            $successful = updateJustificationStatut($idJustification, $config['statut']);
            if ($successful) {
                setSuccess($config['success_message']);
            } else {
                setFieldError("general", $config['error_message']);
            }
            redirectURL("attacher", "justifications");
            return;
        }
    }
}

function handleStudentAttachedData(int $idAttache): array
{
    $data = [
        'filtered' => [
            'id_classe' => $_GET["id_classe"] ?? "",
            'statut' => $_GET["statut"] ?? ''
        ],
        'classes' => getClassesByAttache($idAttache)
    ];
    $data["students"] = getEtudiantsByAttache($idAttache, $data["filtered"]);

    if (isset($_GET["details_student"])) {
        $idEtudiant = $_GET["details_student"];
        $data["details"] = getAbsencesByEtudiant($idEtudiant);
    }
    return $data;
}

function handleInscriptionData(int $idAttache): array
{
    $data = [
        'filtered' => [
            'id_classe' => $_GET['id_classe'] ?? '',
            'annee_scolaire' => $_GET['annee_scolaire'] ?? getAnneeScolaireActuelle(),
            'search' => $_GET['search'] ?? ''
        ],
        'classes' => getClassesByAttache($idAttache),
        'annees_scolaires' => getAnneesScolaires(),
        'periodes' => getDatesPeriodes()
    ];
    $data['etudiants'] = getEtudiantsInscrits($idAttache, $data["filtered"]);
    $data['periode_inscription'] = estEnPeriode('inscription');
    $data['periode_reinscription'] = estEnPeriode('reinscription');
    handleReinscriptionRequest();
    handleInscriptionRequest();
    return $data;
}

function estEnPeriode(string $type): bool
{
    $periodes = getDatesPeriodes();
    $now = new DateTime();

    try {
        $debut = new DateTime($periodes[$type]['debut']);
        $fin = new DateTime($periodes[$type]['fin']);

        return $now >= $debut && $now <= $fin;
    } catch (Exception $e) {
        return false;
    }
}

function handleInscriptionRequest(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrire'])) {
        if (validateInscriptionEtudiant($_POST)) {
            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = uploadAvatar($_FILES['avatar'], 'etudiant', 'etu_');
            }

            $studentData = [
                'nom' => trim($_POST['nom']),
                'prenom' => trim($_POST['prenom']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'telephone' => !empty($_POST['telephone']) ? trim($_POST['telephone']) : null,
                'adresse' => !empty($_POST['adresse']) ? trim($_POST['adresse']) : null,
                'matricule' => trim($_POST['matricule']),
                'id_classe' => (int)$_POST['id_classe'],
                'annee_scolaire' => trim($_POST['annee_scolaire']),
                'avatar' => $avatar
            ];
            // dumpDie($studentData);
            $etudiantId = inscrireNouvelEtudiant($studentData);

            if ($etudiantId) {
                setSuccess("Étudiant inscrit avec succès");
                redirectURL("attacher", "inscriptions");
            } else {
                setFieldError("general", "Une erreur est survenue lors de l'inscription");
                if ($avatar && file_exists(ROOT_PATH . "/public/uploads/etudiant/$avatar")) {
                    unlink(ROOT_PATH . "/public/uploads/etudiant/$avatar");
                }
            }
        }
    }
}

function handleReinscriptionRequest(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reinscrire'])) {
        $idEtudiant = $_POST['id_etudiant'] ?? null;
        $idClasse = $_POST['id_classe'] ?? null;
        $anneeScolaire = $_POST['annee_scolaire'] ?? null;
        $isRedoublement = isset($_POST['redoublement']) && $_POST['redoublement'] === '1';

        $success = reinscrireEtudiant($idEtudiant, $idClasse, $anneeScolaire, $isRedoublement);

        if ($success) {
            $message = $isRedoublement
                ? "Redoublement effectué avec succès"
                : "Réinscription effectuée avec succès";
            setSuccess($message);
            redirectURL("attacher", "inscriptions");
        } else {
            setFieldError('general', 'Erreur lors de la réinscription');
        }
    }
}
