<?php

function getAllAnneesScolaires(array $filters = []): array
{
    $sql = "SELECT * FROM annee_scolaire WHERE 1=1";
    $params = [];

    if (isset($filters['est_active'])) {
        $sql .= " AND est_active = ?";
        $params[] = (bool)$filters['est_active'];
    }

    if (!empty($filters['date_reference'])) {
        $sql .= " AND ? BETWEEN date_debut AND date_fin";
        $params[] = $filters['date_reference'];
    }

    $sort = $filters['sort'] ?? 'desc';
    $sql .= " ORDER BY date_debut " . ($sort === 'asc' ? 'ASC' : 'DESC');

    return fetchResult($sql, $params) ?: [];
}

function getActiveAnneeScolaire()
{
    $sql = "SELECT * FROM annee_scolaire WHERE est_active = 1";
    return fetchResult($sql, [], false) ?: [];
}
