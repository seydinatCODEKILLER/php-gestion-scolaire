<?php
require_once ROOT_PATH . "/models/classe.model.php";
require_once ROOT_PATH . "/models/filiere.model.php";
require_once ROOT_PATH . "/models/niveaux.model.php";
require_once ROOT_PATH . "/models/professeur.model.php";
require_once ROOT_PATH . "/models/annee_scolaire.model.php";


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

        if ($entity === 'cours' && $data['details']) {
            $data['coursClasses'] = getClassesByCours($_GET[$detailKey]);
            $data['coursEtudiants'] = getEtudiantsByCours($_GET[$detailKey]);
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
        if ($entity === 'cours' && $data['toEdit']) {
            $data['coursClassesIds'] = array_column(getClassesByCours($data['toEdit']['id_cours']), 'id_classe');
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
            break;
        case 'cours':
            return [
                'id_cours' => (int)($_POST['id_cours'] ?? 0),
                'id_module' => (int)$_POST['id_module'],
                'id_professeur' => (int)$_POST['id_professeur'],
                'id_semestre' => (int)$_POST['id_semestre'],
                'date_cours' => $_POST['date_cours'],
                'heure_debut' => $_POST['heure_debut'],
                'heure_fin' => $_POST['heure_fin'],
                'nombre_heures' => (int)$_POST['nombre_heures'],
                'salle' => trim($_POST['salle']),
                'statut' => 'planifié',
                'classes' => $_POST['classes'] ?? []
            ];
            break;
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
        case 'cours':
            $isvalid = validateDataAddCours($data);
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
        $entity === "cours" ? redirectURL("responsable", "{$entity}") :  redirectURL("responsable", "{$entity}s");
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
    if (isset($_GET['cancel_cours_id'])) {
        if (toggleCoursStatus($_GET['cancel_cours_id'], 'annulé')) {
            setSuccess("Cours annulé avec succès");
        }
        redirectURL("responsable", "cours");
    }
}
