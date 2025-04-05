<?php
require_once ROOT_PATH . "/models/classe.model.php";
require_once ROOT_PATH . "/models/filiere.model.php";
require_once ROOT_PATH . "/models/niveaux.model.php";
require_once ROOT_PATH . "/models/professeur.model.php";


function initController()
{
    isUserLoggedIn();
    clearFieldErrors();

    return [
        'page' => $_GET['page'] ?? 'dashboard',
        'role' => getDataFromSession("user", "libelle"),
        'contenue' => '',
        'message' => getSuccess(),
        'errors' => getFieldErrors()
    ];
}

function handleCRUD($entity, $defaultFilters = [])
{
    $data = [
        'toEdit' => null,
        'details' => null,
        'formData' => [],
        'filtered' => array_merge($defaultFilters, [
            'state' => $_GET['state'] ?? 'disponible'
        ])
    ];

    if (is_request_method("get")) {
        handleGetRequests($entity, $data);
    }

    if (is_request_method("post")) {
        handlePostRequests($entity, $data);
    }

    return $data;
}

function handleGetRequests($entity, &$data)
{
    // Détails
    $detailKey = "details_{$entity}_id";
    if (isset($_GET[$detailKey])) {
        $func = "get{$entity}Details";
        $data['details'] = $func($_GET[$detailKey]);

        if ($entity === 'professeur' && $data['details']) {
            $data['profClasses'] = getClassesByProfesseur(
                $_GET[$detailKey],
                $_GET['annee'] ?? ""
            );
        }

        !$data['details'] && setFieldError("general", ucfirst($entity) . " introuvable");
    }

    // Edition
    $editKey = "edit_{$entity}_id";
    if (isset($_GET[$editKey])) {
        $func = "get{$entity}ById";
        $data['toEdit'] = $func($_GET[$editKey]);

        if ($entity === 'professeur' && $data['toEdit']) {
            $profDetails = getProfesseurDetails($data['toEdit']['id_professeur']);
            $data['profClassesIds'] = array_column($profDetails['classes'], 'id_classe');
        }
        !$data['toEdit'] && redirectURL("notFound", "error");
    }

    // Actions d'archivage
    handleArchiveActions($entity);
}

function handlePostRequests($entity, &$data)
{
    $data['formData'] = buildFormData($entity);
    if (validateData($entity, $data['formData'])) {
        saveData($entity, $data['formData']);
    }
}

function buildFormData($entity)
{
    switch ($entity) {
        case 'niveau':
            return [
                "id_{$entity}" => (int)($_POST["id_{$entity}"] ?? 0),
                "libelle" => trim($_POST["libelle"] ?? "")
            ];
            break;
        case 'filiere':
            return [
                "id_{$entity}" => (int)($_POST["id_{$entity}"] ?? 0),
                "libelle" => trim($_POST["libelle"] ?? ""),
                "description" => trim($_POST["description"])
            ];
            break;
        case 'classe':
            return [
                "id_classe" => (int)($_POST["id_classe"] ?? 0),
                "libelle" => trim($_POST["libelle"]),
                "filiere" => (int)($_POST["filiere"]),
                "niveau" => (int)($_POST["niveau"]),
                "annee_scolaire" => trim($_POST["annee_scolaire"]),
                "capacite" => (int)($_POST["capacite"])
            ];
            break;
        case 'professeur':
            $avatar = '';
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = uploadAvatar($_FILES['avatar'], "professeur", "pro_");
            } elseif (!empty($_POST['current_avatar'])) {
                $avatar = $_POST['current_avatar'];
            }

            return [
                'id_professeur' => (int)($_POST['id_professeur'] ?? 0),
                'id_utilisateur' => (int)($_POST['id_utilisateur'] ?? 0),
                'nom' => trim($_POST['nom'] ?? ''),
                'prenom' => trim($_POST['prenom'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'telephone' => trim($_POST['telephone'] ?? ''),
                'specialite' => trim($_POST['specialite'] ?? ''),
                'grade' => trim($_POST['grade'] ?? ''),
                'avatar' => $avatar,
                'classes' => $_POST['classes'] ?? []
            ];
    }
}

function validateData($entity, $data)
{
    switch ($entity) {
        case 'filiere':
            $isvalid = validateDataAddFiliere($data);
            return $isvalid;
            break;
        case 'niveau':
            $isvalid = validateDataAddNiveau($data);
            return $isvalid;
            break;
        case 'classe':
            $isvalid = validateDataAddClasse($data);
            return $isvalid;
            break;
        case 'professeur':
            $isvalid = validateDataAddProfesseur($data);
            return $isvalid;
            break;
    }
}

function saveData($entity, $data)
{
    $isUpdate = $data["id_{$entity}"] > 0;
    $func = $isUpdate ? "update{$entity}" : "create{$entity}";

    if ($func($data)) {
        setSuccess($isUpdate
            ? ucfirst($entity) . " modifié avec succès"
            : ucfirst($entity) . " ajouté avec succès");
        redirectURL("responsable", "{$entity}s");
    } else {
        setFieldError("general", "Erreur lors de l'enregistrement");
    }
}

function handleArchiveActions($entity)
{
    $capitalizedEntity = ucfirst($entity);

    $actions = [
        "archived_{$entity}_id" => 'archiver',
        "unarchive_{$entity}_id" => 'disponible'
    ];

    foreach ($actions as $param => $status) {
        if (isset($_GET[$param])) {
            $func = "toggle{$capitalizedEntity}Status";
            if ($func($_GET[$param], $status)) {
                setSuccess(ucfirst($entity) . " " . ($status === 'archiver' ? "archivé" : "désarchivé") . " avec succès");
            }
            redirectURL("responsable", "{$entity}s");
        }
    }
}
