<?php

function getAllNiveaux()
{
    $sql = "SELECT * FROM niveaux WHERE state = 'disponible'";
    return fetchResult($sql);
}

function getAllNiveau($filters = [], $page = 1, $perPage = 5)
{
    $sql = "SELECT * FROM niveaux";
    $where = [];
    $params = [];

    if (!empty($filters['search'])) {
        $where[] = "libelle LIKE ?";
        $params[] = '%' . $filters['search'] . '%';
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY libelle";
    return paginateQuery($sql, $params, $page, $perPage);
}

function getNiveauById($id_niveau)
{
    $sql = "SELECT * FROM niveaux WHERE id_niveau = ?";
    return fetchResult($sql, [$id_niveau], false);
}

function createNiveau(array $data)
{
    $sql = "INSERT INTO niveaux (libelle) VALUES (?)";
    return executeQuery($sql, [$data['libelle']]) !== false;
}

function updateNiveau(array $data)
{
    $sql = "UPDATE niveaux SET libelle = ? WHERE id_niveau = ?";
    return executeQuery($sql, [$data['libelle'], $data['id_niveau']]) !== false;
}

function toggleNiveauStatus($id_niveau, string $status)
{
    $sql = "UPDATE niveaux SET state = ? WHERE id_niveau = ?";
    return executeQuery($sql, [$status, $id_niveau]) !== false;
}

function findNiveauByLibelle(string $libelle): array | false
{
    $sql = "SELECT * FROM niveaux WHERE libelle = ?";
    return fetchResult($sql, [$libelle], false);
}
