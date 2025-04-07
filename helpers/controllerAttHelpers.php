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

/**
 * Récupère les détails d'une classe spécifique
 */
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

/**
 * Récupère les données pour le tableau de bord de l'Attaché
 * @param int $idAttache - ID de l'utilisateur attaché
 * @return array - Données structurées pour le dashboard
 */
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
