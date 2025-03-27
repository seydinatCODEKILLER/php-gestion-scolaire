<?php
require_once ROOT_PATH . "/data/db.php";

function executeQuery(string $sql, array $params = []): PDOStatement|false
{
    try {
        $pdo = connectDB();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die("Erreur est survenue lors de l'execution de la requete: " . $e->getMessage());
    }
}

function fetchResult(string $sql, array $params = [], bool $all = true): array | false
{
    $stmt = executeQuery($sql, $params);
    if ($stmt) {
        if ($all) {
            return $stmt->fetchAll();
        }
        return $stmt->fetch();
    }
    return false;
}
