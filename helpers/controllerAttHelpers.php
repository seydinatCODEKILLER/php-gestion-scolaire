<?php

require_once ROOT_PATH . "/models/attacher.model.php";


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
