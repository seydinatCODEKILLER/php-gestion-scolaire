<?php
function countCoursThisMonth()
{
    $sql = "SELECT COUNT(*) nb_cours FROM cours WHERE MONTH(date_cours) = MONTH(CURRENT_DATE())";
    return fetchResult($sql, [], false);
}

function getCoursByProfesseur($id_professeur)
{
    $sql = "SELECT * FROM cours WHERE id_professeur = :id_professeur";
    $params = [":id_professeur" => $id_professeur];
    return fetchResult($sql, $params);
}

function getCoursDetails($id_cours)
{
    $sql = "SELECT c.*, m.libelle as module_libelle, 
            CONCAT(u.prenom, ' ', u.nom) as professeur_nom
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN professeurs p ON c.id_professeur = p.id_professeur
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE c.id_cours = ?";

    $cours = fetchResult($sql, [$id_cours], false);
    if (!$cours) return false;

    return [
        'info' => $cours,
        'classes' => getClassesByCours($id_cours),
        'etudiants' => getEtudiantsByCours($id_cours)
    ];
}

function getAllCours($filters = [], $page = 1, $perPage = 10)
{
    $sql = "SELECT c.*, m.libelle as module_libelle, p.nom as professeur_nom, p.prenom as professeur_prenom 
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN professeurs pr ON c.id_professeur = pr.id_professeur
            JOIN utilisateurs p ON pr.id_utilisateur = p.id_utilisateur";

    $where = [];
    $params = [];

    // Filtres
    if (!empty($filters['statut'])) {
        $where[] = "c.statut = ?";
        $params[] = $filters['statut'];
    }

    if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
        $where[] = "c.date_cours BETWEEN ? AND ?";
        $params[] = $filters['date_debut'];
        $params[] = $filters['date_fin'];
    }

    if (!empty($filters['id_classe'])) {
        $sql .= " JOIN cours_classes cc ON c.id_cours = cc.id_cours";
        $where[] = "cc.id_classe = ?";
        $params[] = $filters['id_classe'];
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY c.date_cours DESC, c.heure_debut";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getCoursById($id_cours)
{
    $sql = "SELECT c.*, m.libelle as module_libelle, 
            CONCAT(u.prenom, ' ', u.nom) as professeur_nom
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN professeurs p ON c.id_professeur = p.id_professeur
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE c.id_cours = ?";
    return fetchResult($sql, [$id_cours], false);
}

function getClassesByCours($id_cours)
{
    $sql = "SELECT cl.* FROM classes cl
            JOIN cours_classes cc ON cl.id_classe = cc.id_classe
            WHERE cc.id_cours = ?";
    return fetchResult($sql, [$id_cours]);
}

function getEtudiantsByCours(int $idCours): array
{
    $sql = "SELECT e.id_etudiant, e.matricule, 
                   u.nom, u.prenom, u.avatar,
                   c.libelle as libelle_classe,
                   IFNULL(a.id_absence, 0) as absent
            FROM cours_classes cc
            JOIN inscriptions i ON cc.id_classe = i.id_classe
            JOIN etudiants e ON i.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN classes c ON cc.id_classe = c.id_classe
            LEFT JOIN absences a ON (a.id_etudiant = e.id_etudiant AND a.id_cours = cc.id_cours)
            WHERE cc.id_cours = ?
            ORDER BY u.nom, u.prenom";

    return fetchResult($sql, [$idCours]);
}

function createCours($data)
{
    // 1. Créer le cours principal
    $sql = "INSERT INTO cours (id_module, id_professeur, id_semestre, date_cours, heure_debut, heure_fin, nombre_heures, salle, statut)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $data['id_module'],
        $data['id_professeur'],
        $data['id_semestre'],
        $data['date_cours'],
        $data['heure_debut'],
        $data['heure_fin'],
        $data['nombre_heures'],
        $data['salle'],
        $data['statut'] ?? 'planifié'
    ];

    $id_cours = executeQuery($sql, $params, true);
    if (!$id_cours) {
        return false;
    }

    if (!empty($data['classes'])) {
        foreach ($data['classes'] as $id_classe) {
            if (!addClasseToCours($id_cours, $id_classe)) {
                executeQuery("DELETE FROM cours WHERE id_cours = ?", [$id_cours]);
                return false;
            }
        }
    }

    return true;
}

function updateCours($data)
{
    // 1. Mettre à jour le cours principal
    $sql = "UPDATE cours SET 
            id_module = ?,
            id_professeur = ?,
            id_semestre = ?,
            date_cours = ?,
            heure_debut = ?,
            heure_fin = ?,
            nombre_heures = ?,
            salle = ?,
            statut = ?
            WHERE id_cours = ?";

    $params = [
        $data['id_module'],
        $data['id_professeur'],
        $data['id_semestre'],
        $data['date_cours'],
        $data['heure_debut'],
        $data['heure_fin'],
        $data['nombre_heures'],
        $data['salle'],
        $data['statut'],
        $data['id_cours']
    ];

    if (!executeQuery($sql, $params)) {
        return false;
    }

    if (isset($data['classes'])) {
        executeQuery("DELETE FROM cours_classes WHERE id_cours = ?", [$data['id_cours']]);
        foreach ($data['classes'] as $id_classe) {
            if (!addClasseToCours($data['id_cours'], $id_classe)) {
                return false;
            }
        }
    }

    return true;
}

function toggleCoursStatus($id_cours, $statut)
{
    $sql = "UPDATE cours SET statut = ? WHERE id_cours = ?";
    return executeQuery($sql, [$statut, $id_cours]);
}

function addClasseToCours($id_cours, $id_classe)
{
    $sql = "INSERT INTO cours_classes (id_cours, id_classe) VALUES (?, ?)";
    return executeQuery($sql, [$id_cours, $id_classe]);
}

function removeClasseFromCours($id_cours, $id_classe)
{
    $sql = "DELETE FROM cours_classes WHERE id_cours = ? AND id_classe = ?";
    return executeQuery($sql, [$id_cours, $id_classe]);
}

function checkProfesseurDisponibilite($id_professeur, $date, $heure_debut, $heure_fin, $exclude_cours_id = null)
{
    $sql = "SELECT COUNT(*) as count FROM cours 
            WHERE id_professeur = ? 
            AND date_cours = ?
            AND ((heure_debut < ? AND heure_fin > ?) OR (heure_debut < ? AND heure_fin > ?))
            AND statut != 'annulé'";

    $params = [$id_professeur, $date, $heure_fin, $heure_debut, $heure_fin, $heure_debut];

    if ($exclude_cours_id) {
        $sql .= " AND id_cours != ?";
        $params[] = $exclude_cours_id;
    }

    $result = fetchResult($sql, $params, false);
    return $result['count'] == 0;
}

function checkSalleDisponibilite($salle, $date, $heure_debut, $heure_fin, $exclude_cours_id = null)
{
    $sql = "SELECT COUNT(*) as count FROM cours 
            WHERE salle = ? 
            AND date_cours = ?
            AND ((heure_debut < ? AND heure_fin > ?) OR (heure_debut < ? AND heure_fin > ?))
            AND statut != 'annulé'";

    $params = [$salle, $date, $heure_fin, $heure_debut, $heure_fin, $heure_debut];

    if ($exclude_cours_id) {
        $sql .= " AND id_cours != ?";
        $params[] = $exclude_cours_id;
    }

    $result = fetchResult($sql, $params, false);
    return $result['count'] == 0;
}
