<?php
function getAllSemestres($filters = [])
{
    $sql = "SELECT * FROM semestres";
    $where = [];
    $params = [];

    // Filtre par année scolaire si spécifié
    if (!empty($filters['annee_scolaire'])) {
        $where[] = "annee_scolaire = ?";
        $params[] = $filters['annee_scolaire'];
    }

    // Filtre pour les semestres actifs
    if (!empty($filters['actifs'])) {
        $where[] = "date_fin >= CURDATE()";
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY date_debut DESC";

    return fetchResult($sql, $params);
}

function getCurrentSemestre()
{
    $sql = "SELECT * FROM semestres 
            WHERE date_debut <= CURDATE() AND date_fin >= CURDATE()
            LIMIT 1";
    return fetchResult($sql, [], false);
}
