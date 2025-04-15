<?php

function countClasses()
{
    $sql = "SELECT COUNT(*) nb_classes FROM classes";
    return fetchResult($sql, [], false);
}

function getAllClasses()
{
    $sql = "SELECT * FROM classes WHERE state = 'disponible'";
    return fetchResult($sql);
}

function toggleClasseStatus(int $idClasse, string $newStatus): bool
{
    $sql = "UPDATE classes SET state = ? WHERE id_classe = ?";
    return executeQuery($sql, [$newStatus, $idClasse]) !== false;
}

function getFilteredClasses($filters = [], $page = 1, $perPage = 5)
{
    $sql = "
        SELECT c.*, f.libelle as filiere, n.libelle as niveau, an.libelle annee_scolaire,
        COUNT(DISTINCT e.id_etudiant) as effectif
        FROM classes c
        JOIN filieres f ON c.id_filiere = f.id_filiere
        JOIN annee_scolaire an ON an.id_annee = c.id_annee
        JOIN niveaux n ON c.id_niveau = n.id_niveau
        LEFT JOIN inscriptions i ON c.id_classe = i.id_classe AND i.statut = 'validée'
        LEFT JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    ";

    $where = [];
    $params = [];

    if (!empty($filters['filiere'])) {
        $where[] = "c.id_filiere = ?";
        $params[] = $filters['filiere'];
    }

    if (!empty($filters['niveau'])) {
        $where[] = "c.id_niveau = ?";
        $params[] = $filters['niveau'];
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " GROUP BY c.id_classe ORDER BY c.libelle";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getClasseDetails(int $idClasse): array | false
{
    $sql = "SELECT c.*, 
        f.libelle as filiere, 
        n.libelle as niveau,
        an.libelle as annee_scolaire,
        COUNT(DISTINCT e.id_etudiant) as effectif,
        GROUP_CONCAT(DISTINCT CONCAT(u_prof.prenom, ' ', u_prof.nom) SEPARATOR ', ') as professeurs,
        GROUP_CONCAT(DISTINCT CONCAT(u_etud.prenom, ' ', u_etud.nom) SEPARATOR ', ') as etudiants
        FROM classes c
        JOIN filieres f ON c.id_filiere = f.id_filiere
        JOIN niveaux n ON c.id_niveau = n.id_niveau
        JOIN annee_scolaire an ON an.id_annee = c.id_annee
        LEFT JOIN classes_professeur cp ON c.id_classe = cp.id_classe
        LEFT JOIN professeurs p ON cp.id_professeur = p.id_professeur
        LEFT JOIN utilisateurs u_prof ON p.id_utilisateur = u_prof.id_utilisateur
        LEFT JOIN inscriptions i ON c.id_classe = i.id_classe AND i.statut = 'validée'
        LEFT JOIN etudiants e ON i.id_etudiant = e.id_etudiant
        LEFT JOIN utilisateurs u_etud ON e.id_utilisateur = u_etud.id_utilisateur
        WHERE c.id_classe = ?
        GROUP BY c.id_classe";

    return fetchResult($sql, [$idClasse], false);
}

function createClasse(array $data)
{
    $sql = "INSERT INTO classes 
            (libelle, id_filiere, id_niveau, id_annee, capacite_max) 
            VALUES (?, ?, ?, ?, ?)";
    $params = [
        $data['libelle'],
        $data['filiere'],
        $data['niveau'],
        $data['annee_scolaire'],
        $data['capacite']
    ];

    $stmt = executeQuery($sql, $params);
    return $stmt !== false;
}

function updateClasse(array $data): bool
{
    $sql = "UPDATE classes SET 
            libelle = ?,
            id_filiere = ?,
            id_niveau = ?,
            annee_scolaire = ?,
            capacite_max = ?
            WHERE id_classe = ?";

    $params = [
        $data['libelle'],
        $data['filiere'],
        $data['niveau'],
        $data['annee_scolaire'],
        $data['capacite'],
        $data['id_classe']
    ];

    $stmt = executeQuery($sql, $params);
    return $stmt !== false;
}

function getClasseById($id_classe)
{
    $sql = "
        SELECT c.*, f.libelle as filiere, n.libelle as niveau
        FROM classes c
        JOIN filieres f ON c.id_filiere = f.id_filiere
        JOIN niveaux n ON c.id_niveau = n.id_niveau
        WHERE c.id_classe = ?
    ";
    $params = [$id_classe];
    return fetchResult($sql, $params, false);
}

function getEtudiantsByClasse($id_classe)
{
    $sql = "
        SELECT e.*, u.nom, u.prenom, u.email
        FROM etudiants e
        JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
        JOIN inscriptions i ON e.id_etudiant = i.id_etudiant
        WHERE i.id_classe = :id_classe AND i.statut = 'validée'
    ";
    $params = [":id_classe" => $id_classe];
    return fetchResult($sql, $params);
}

function findClasseByLibelle(string $libelle): array | false
{
    $sql = "SELECT * FROM classes WHERE libelle LIKE ?";
    $params = [$libelle];
    return fetchResult($sql, $params, false);
}
