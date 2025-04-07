<?php

function getJustificationsByAttache(int $idAttache, $filters = [], $page = 1, $perPage = 10): array
{
    $sql = "SELECT j.id_justification, j.date_justification, j.statut, j.pieces_jointes, 
            u.nom, u.prenom, c.libelle AS classe, m.libelle, a.date_absence
            FROM justifications j
            JOIN absences a ON j.id_absence = a.id_absence
            JOIN cours cr ON a.id_cours = cr.id_cours
            JOIN modules m ON cr.id_module = m.id_module
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON u.id_utilisateur = e.id_utilisateur
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            WHERE ca.id_attache = ?";

    $where = [];
    $params = [];

    if ($idAttache) {
        $params[] = $idAttache;
    }

    if (!empty($filters['statut'])) {
        $where[] = "j.statut = ?";
        $params[] = $filters['statut'];
    }

    if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
        $where[] = "j.date_justification BETWEEN ? AND ?";
        $params[] = $filters['date_debut'];
        $params[] = $filters['date_fin'];
    }

    if (!empty($where)) {
        $sql .= " AND " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY j.date_justification DESC";
    return paginateQuery($sql, $params, $page, $perPage);
}

function updateJustificationStatut(int $id_justification, string $statut): bool
{
    $sql = "UPDATE justifications SET statut = ? WHERE id_justification = ?";
    $isUpdated = executeQuery($sql, [$statut, $id_justification]);
    if (!$isUpdated) return false;
    return true;
}
