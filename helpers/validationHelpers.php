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

function validateDataAddProfesseur(array $data): bool
{

    // Validation du nom
    if (empty($data['nom'])) {
        setFieldError('nom', "Le nom est obligatoire");
    } elseif (strlen($data['nom']) > 100) {
        setFieldError('nom', "Le nom ne doit pas dépasser 100 caractères");
    }

    if (empty($data['prenom'])) {
        setFieldError('prenom', "Le prénom est obligatoire");
    } elseif (strlen($data['prenom']) > 100) {
        setFieldError('prenom', "Le prénom ne doit pas dépasser 100 caractères");
    }

    // Validation de l'email
    if (empty($data['email'])) {
        setFieldError('email', "L'email est obligatoire");
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        setFieldError('email', "L'email n'est pas valide");
    } elseif (strlen($data['email']) > 150) {
        setFieldError('email', "L'email ne doit pas dépasser 150 caractères");
    }

    // Validation du mot de passe (seulement pour les nouveaux professeurs)
    if (empty($data['id_professeur']) && empty($data['password'])) {
        setFieldError('password', "Le mot de passe est obligatoire");
    } elseif (!empty($data['password']) && strlen($data['password']) < 5) {
        setFieldError('password', "Le mot de passe doit contenir au moins 5 caractères");
    }

    // Validation de la spécialité
    if (empty($data['specialite'])) {
        setFieldError('specialite', "La spécialité est obligatoire");
    } elseif (strlen($data['specialite']) > 100) {
        setFieldError('specialite', "La spécialité ne doit pas dépasser 100 caractères");
    }

    // Validation du grade
    if (empty($data['grade'])) {
        setFieldError('grade', "Le grade est obligatoire");
    } elseif (strlen($data['grade']) > 50) {
        setFieldError('grade', "Le grade ne doit pas dépasser 50 caractères");
    }

    // Validation du téléphone
    if (!empty($data['telephone']) && !preg_match('/^[0-9 +-]{8,20}$/', $data['telephone'])) {
        setFieldError('telephone', "Le format du téléphone n'est pas valide");
    }

    // Validation du téléphone
    if (empty($data['telephone'])) {
        setFieldError('telephone', "Le telephone est obligatoire");
    }

    // Validation du photo
    if (empty($data['avatar'])) {
        setFieldError('avatar', "L'avatar est obligatoire");
    }

    // Validation des classes (seulement pour les nouveaux professeurs ou si modification explicite)
    if ((empty($data['id_professeur']) || isset($data['classes'])) && empty($data['classes'])) {
        setFieldError('classes', "Vous devez sélectionner au moins une classe");
    }

    return empty(getFieldErrors());
}
