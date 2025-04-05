<?php


function initController()
{
    isUserLoggedIn();
    clearFieldErrors();

    return [
        'page' => $_GET['page'] ?? 'dashboard',
        'role' => getDataFromSession("user", "libelle"),
        'userId' => getDataFromSession("user", "id_utilisateur"),
        'contenue' => '',
        'message' => getSuccess(),
        'errors' => getFieldErrors()
    ];
}

function handleCRUD($entity, $defaultFilters = []) {}

function handleGetRequests($entity, &$data) {}

function handlePostRequests($entity, &$data) {}

function buildFormData($entity) {}

function validateData($entity, $data) {}

function saveData($entity, $data) {}

function handleArchiveActions($entity) {}
