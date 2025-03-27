<?php

function paginateResults(string $table, int $perPage = 5, int $currentPage = 1): array
{
    global $pdo;
    $offset = ($currentPage - 1) * $perPage;
    $stmt = $pdo->prepare("SELECT * FROM $table LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalItems = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    $totalPages = ceil($totalItems / $perPage);
    return [
        'items' => $items,
        'total_pages' => $totalPages,
        'current_page' => $currentPage
    ];
}

function dumpDie(array $data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function redirectUserByRole(string $role)
{
    switch ($role) {
        case 'gestionnaire':
            redirectURL("gestionnaire", "dashboard");
            break;
        case 'client':
            redirectURL("client", "dashboard");
            break;
        case 'visiteur':
            redirectURL("visiteur", "dashboard");
            break;
        default:
            redirectURL("security", "connexion");
            break;
    }
}

function is_valid_number($data): bool
{
    if (!is_numeric($data) || $data < 0) {
        return false;
    }
    return true;
}

function is_request_method(string $method): bool
{
    return $_SERVER["REQUEST_METHOD"] === strtoupper($method);
}

function redirectURL(string $controller, string $page, array $additionalParams = [])
{
    $params = [
        'controllers' => $controller,
        'page' => $page
    ];
    $params = array_merge($params, $additionalParams);
    $queryString = http_build_query($params);
    header("Location: ?" . $queryString);
    exit;
}
