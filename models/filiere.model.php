<?php

function getAllFileres()
{
    $sql = "SELECT * FROM filieres WHERE state = 'disponible'";
    return fetchResult($sql);
}

function countFilieres()
{
    $sql = "SELECT COUNT(*) nb_filieres FROM filieres";
    return fetchResult($sql, [], false);
}

function getAllFilieres($filters = [], $page = 1, $perPage = 5)
{
    $sql = "SELECT * FROM filieres";
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

function getFiliereById($id_filiere)
{
    $sql = "SELECT * FROM filieres WHERE id_filiere = ?";
    return fetchResult($sql, [$id_filiere], false);
}

function createFiliere($data)
{
    $sql = "INSERT INTO filieres (libelle, description,date_creation) VALUES (?, ?, ?)";
    $params = [
        $data['libelle'],
        $data['description'],
        date('Y-m-d')
    ];
    return executeQuery($sql, $params) !== false;
}

function toggleFiliereStatus(int $idFiliere, string $newStatus): bool
{
    $sql = "UPDATE filieres SET state = ? WHERE id_filiere = ?";
    return executeQuery($sql, [$newStatus, $idFiliere]) !== false;
}

function updateFiliere($data)
{
    $sql = "UPDATE filieres SET libelle = ?, description = ? WHERE id_filiere = ?";
    $params = [
        $data['libelle'],
        $data['description'],
        $data['id_filiere']
    ];
    return executeQuery($sql, $params) !== false;
}

function deleteFiliere($id_filiere)
{
    $sql = "DELETE FROM filieres WHERE id_filiere = ?";
    return executeQuery($sql, [$id_filiere]) !== false;
}

function findFiliereByLibelle($libelle)
{
    $sql = "SELECT * FROM filieres WHERE libelle = ?";
    return fetchResult($sql, [$libelle], false);
}
