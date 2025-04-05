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

function dumpDie(mixed $data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function redirectUserByRole(string $role)
{
    switch ($role) {
        case 'RP':
            redirectURL("responsable", "dashboard");
            break;
        case 'Professeur':
            redirectURL("professeur", "dashboard");
            break;
        case 'Attache':
            redirectURL("attacher", "dashboard");
            break;
        case 'Etudiant':
            redirectURL("etudiant", "dashboard");
            break;
        default:
            redirectURL("security", "connexion");
            break;
    }
}

function redirectAfterError(string $role)
{
    switch ($role) {
        case 'RP':
            return "controllers=responsable&page=dashboard";
        case 'Professeur':
            return "controllers=professeur&page=dashboard";
        case 'Attache':
            return "controllers=attacher&page=dashboard";
        case 'Etudiant':
            return "controllers=etudiant&page=dashboard";
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

function uploadAvatar(array $file, string $role, string $prefix): string
{
    $uploadDir = ROOT_PATH . "public/uploads/$role/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid($prefix) . '.' . $extension;
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Échec de l'upload du fichier");
    }

    return $filename;
}

function deleteAvatar(string $filename, string $role): bool
{
    if (!empty($filename)) {
        $filePath = ROOT_PATH . "public/uploads/$role/" . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
    }
    return false;
}
