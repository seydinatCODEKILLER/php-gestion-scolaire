<?php

function countClassesByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(*) as nb_classes 
            FROM classes_attaches 
            WHERE id_attache = ?";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['nb_classes'] : 0;
}


function countEtudiantsByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(DISTINCT e.id_etudiant) as nb_etudiants
            FROM classes_attaches ca
            JOIN classes c ON ca.id_classe = c.id_classe
            JOIN etudiants e ON e.id_classe = c.id_classe
            WHERE ca.id_attache = ?";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['nb_etudiants'] : 0;
}


function countJustificationsEnAttenteByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(*) as justifications_en_attente
            FROM justifications j
            JOIN absences a ON j.id_absence = a.id_absence
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN classes_attaches ca ON ca.id_classe = c.id_classe
            WHERE ca.id_attache = ? AND j.statut = 'en_attente'";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['justifications_en_attente'] : 0;
}

function getDernieresAbsencesByAttache(int $idAttache, int $limit = 5): array
{
    $sql = "SELECT a.date_absence, u.nom, u.prenom, c.libelle AS classe, m.libelle AS module
            FROM absences a
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            JOIN cours co ON a.id_cours = co.id_cours
            JOIN modules m ON co.id_module = m.id_module
            WHERE ca.id_attache = ?
            ORDER BY a.date_absence DESC
            LIMIT ?";

    return fetchResult($sql, [$idAttache, $limit]);
}


function getIdAttacheByIdUtilisateur(int $idUtilisateur): ?int
{
    $sql = "SELECT id_attache FROM attaches WHERE id_utilisateur = ?";
    $result = fetchResult($sql, [$idUtilisateur], false);
    return $result ? (int) $result['id_attache'] : null;
}

/**
 * Récupère les classes avec leurs statistiques
 */
function getClassesWithStats(int $idAttache, array $filters = []): array
{
    $sql = "SELECT c.id_classe, c.libelle, c.annee_scolaire,
            COUNT(DISTINCT e.id_etudiant) as nb_etudiants,
            COUNT(DISTINCT a.id_absence) as nb_absences,
            f.libelle as filiere
            FROM classes c
            JOIN filieres f ON c.id_filiere = f.id_filiere
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            LEFT JOIN inscriptions i ON c.id_classe = i.id_classe
            LEFT JOIN etudiants e ON i.id_etudiant = e.id_etudiant
            LEFT JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            LEFT JOIN absences a ON e.id_etudiant = a.id_etudiant
            WHERE ca.id_attache = ?";

    $params = [$idAttache];

    if (!empty($filters['search'])) {
        $sql .= " AND (c.libelle LIKE ? OR f.libelle LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        array_push($params, $searchTerm, $searchTerm);
    }

    $sql .= " GROUP BY c.id_classe
            ORDER BY c.libelle";

    return fetchResult($sql, $params);
}
