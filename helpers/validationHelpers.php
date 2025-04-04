<?php

function validateDataLogin(array $data): bool
{
    if (empty($data["email"])) {
        setFieldError('email', "l'adresse mail est requise");
    }
    if (empty($data["password"])) {
        setFieldError('password', "le password est requise");
    }

    return empty(getFieldErrors());
}

function validateDataAddClasse(array $data): bool
{
    if (empty($data["libelle"])) {
        setFieldError('libelle', "le libelle est requise");
    } elseif (findClasseByLibelle($data["libelle"])) {
        setFieldError('libelle', "une classe avec ce libelle existe deja");
    }
    if (empty($data['filiere'])) {
        setFieldError('filiere', "La filière est obligatoire");
    }
    if (empty($data['niveau'])) {
        setFieldError('niveau', "Le niveau est obligatoire");
    }
    if (empty($data['annee_scolaire'])) {
        setFieldError('annee_scolaire', "L'année scolaire est obligatoire");
    } elseif (!preg_match('/^\d{4}-\d{4}$/', $data['annee_scolaire'])) {
        setFieldError('annee_scolaire', "Format d'année scolaire invalide (ex: 2023-2024)");
    }
    if (empty($data['capacite'])) {
        setFieldError('capacite', "La capacite est obligatoire");
    }
    return empty(getFieldErrors());
}

function validateDataAddFiliere(array $data): bool
{
    if (empty($data["libelle"])) {
        setFieldError('libelle', "le libelle est requise");
    } elseif (findFiliereByLibelle($data["libelle"])) {
        setFieldError('libelle', "une filiere avec ce libelle existe deja");
    }
    return empty(getFieldErrors());
}
function validateDataAddNiveau(array $data): bool
{
    if (empty($data["libelle"])) {
        setFieldError('libelle', "le libelle est requise");
    } elseif (findNiveauByLibelle($data["libelle"])) {
        setFieldError('libelle', "une filiere avec ce libelle existe deja");
    }
    return empty(getFieldErrors());
}
