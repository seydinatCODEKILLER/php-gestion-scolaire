<?php
function countProfesseurs()
{
    $sql = "SELECT COUNT(*) nb_professeurs FROM professeurs";
    return fetchResult($sql, [], false);
}

function getAllProfesseurs($filters = [], $page = 1, $perPage = 10)
{
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar, u.state
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE 1=1";

    $params = [];

    if (!empty($filters['search'])) {
        $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR p.specialite LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
    }

    $sql .= " ORDER BY u.nom, u.prenom";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getProfesseurDetails(int $idProfesseur): array
{
    // Récupérer les infos de base du professeur
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar 
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE p.id_professeur = ?";

    $professeur = fetchResult($sql, [$idProfesseur], false);

    if (!$professeur) {
        return [];
    }

    // Récupérer les classes affectées
    $sqlClasses = "SELECT c.id_classe, c.libelle, c.annee_scolaire, 
                    f.libelle as filiere, n.libelle as niveau
                   FROM classes_professeur cp
                   JOIN classes c ON cp.id_classe = c.id_classe
                   JOIN filieres f ON c.id_filiere = f.id_filiere
                   JOIN niveaux n ON c.id_niveau = n.id_niveau
                   WHERE cp.id_professeur = ?";

    $classes = fetchResult($sqlClasses, [$idProfesseur]);

    return [
        'professeur' => $professeur,
        'classes' => $classes
    ];
}

function getProfesseurById($id)
{
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar, u.id_role
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE p.id_professeur = ?";
    return fetchResult($sql, [$id], false);
}

function createProfesseur(array $data)
{
    // D'abord créer l'utilisateur
    $sqlUser = "INSERT INTO utilisateurs 
                (nom, prenom, email, password, id_role, avatar, telephone) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    $paramsUser = [
        $data['nom'],
        $data['prenom'],
        $data['email'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        2, // ID rôle professeur
        $data['avatar'],
        $data['telephone']
    ];

    // On demande explicitement le lastInsertId
    $userId = executeQuery($sqlUser, $paramsUser, true);

    if ($userId === false) {
        return false;
    }

    // Puis créer le professeur
    $sqlProf = "INSERT INTO professeurs 
                (id_utilisateur, specialite, grade, date_embauche) 
                VALUES (?, ?, ?, ?)";
    $paramsProf = [
        $userId,
        $data['specialite'],
        $data['grade'],
        date('Y-m-d')
    ];

    // On demande aussi le lastInsertId pour le professeur
    $profId = executeQuery($sqlProf, $paramsProf, true);

    if ($profId === false) {
        return false;
    }

    // Affecter les classes
    if (!empty($data['classes'])) {
        foreach ($data['classes'] as $idClasse) {
            $sqlAffect = "INSERT INTO classes_professeur 
                          (id_classe, id_professeur, date_affectation) 
                          VALUES (?, ?, NOW())";
            $success = executeQuery($sqlAffect, [$idClasse, $profId]);
            if (!$success) {
                return false;
            }
        }
    }

    return true;
}

function updateProfesseur(array $data): bool
{
    // Mettre à jour l'utilisateur
    $sqlUser = "UPDATE utilisateurs SET 
                nom = ?, prenom = ?, email = ?, telephone = ?, avatar = ?
                WHERE id_utilisateur = ?";
    $paramsUser = [
        $data['nom'],
        $data['prenom'],
        $data['email'],
        $data['telephone'],
        $data['avatar'],
        $data['id_utilisateur']
    ];

    $userUpdated = executeQuery($sqlUser, $paramsUser);
    if ($userUpdated === false) {
        return false;
    }

    // Mettre à jour le professeur
    $sqlProf = "UPDATE professeurs SET 
                specialite = ?, grade = ?
                WHERE id_professeur = ?";
    $paramsProf = [
        $data['specialite'],
        $data['grade'],
        $data['id_professeur']
    ];

    $profUpdated = executeQuery($sqlProf, $paramsProf);
    if ($profUpdated === false) {
        return false;
    }

    // Gestion des classes
    // 1. Supprimer toutes les affectations existantes
    $sqlDelete = "DELETE FROM classes_professeur WHERE id_professeur = ?";
    $deleted = executeQuery($sqlDelete, [$data['id_professeur']]);
    if ($deleted === false) {
        return false;
    }

    // 2. Ajouter les nouvelles affectations
    if (!empty($data['classes'])) {
        foreach ($data['classes'] as $idClasse) {
            $sqlInsert = "INSERT INTO classes_professeur 
                          (id_classe, id_professeur, date_affectation) 
                          VALUES (?, ?, NOW())";
            $inserted = executeQuery($sqlInsert, [$idClasse, $data['id_professeur']]);
            if ($inserted === false) {
                return false;
            }
        }
    }

    return true;
}

function toggleProfesseurStatus(int $idProfesseur, string $newStatus): bool
{
    $sql = "UPDATE utilisateurs SET state = ? WHERE id_utilisateur = 
            (SELECT id_utilisateur FROM professeurs WHERE id_professeur = ?)";
    return executeQuery($sql, [$newStatus, $idProfesseur]) !== false;
}

function getClassesByProfesseur(int $idProfesseur, string $annee)
{
    $sql = "SELECT c.*, f.libelle as filiere, n.libelle as niveau
            FROM classes_professeur cp
            JOIN classes c ON cp.id_classe = c.id_classe
            JOIN filieres f ON c.id_filiere = f.id_filiere
            JOIN niveaux n ON c.id_niveau = n.id_niveau
            WHERE cp.id_professeur = ?";

    $params = [$idProfesseur];

    if ($annee) {
        $sql .= " AND c.annee_scolaire = ?";
        $params[] = $annee;
    }

    return fetchResult($sql, $params);
}
