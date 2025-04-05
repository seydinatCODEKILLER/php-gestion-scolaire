<?php
function getAllModules($filters = [])
{
    $sql = "SELECT m.*, f.libelle as filiere_libelle, n.libelle as niveau_libelle 
            FROM modules m
            JOIN filieres f ON m.id_filiere = f.id_filiere
            JOIN niveaux n ON m.id_niveau = n.id_niveau";

    $where = [];
    $params = [];

    // Filtre par filière si spécifié
    if (!empty($filters['id_filiere'])) {
        $where[] = "m.id_filiere = ?";
        $params[] = $filters['id_filiere'];
    }

    // Filtre par niveau si spécifié
    if (!empty($filters['id_niveau'])) {
        $where[] = "m.id_niveau = ?";
        $params[] = $filters['id_niveau'];
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY m.libelle";

    return fetchResult($sql, $params);
}

function getModuleById($id_module)
{
    $sql = "SELECT * FROM modules WHERE id_module = ?";
    return fetchResult($sql, [$id_module], false);
}
