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

function validateDataAddCours(array $data): bool
{
    if (empty($data['id_module'])) {
        setFieldError('id_module',  "Veuillez sélectionner un module");
    }

    if (empty($data['id_professeur'])) {
        setFieldError('id_professeur', "Veuillez sélectionner un professeur");
    }

    if (empty($data['id_semestre'])) {
        setFieldError('id_semestre', "Veuillez sélectionner un semestre");
    }

    if (empty($data['date_cours'])) {
        setFieldError('date_cours', "La date du cours est obligatoire");
    } elseif (strtotime($data['date_cours']) < strtotime('today')) {
        setFieldError('date_cours', "La date du cours doit être au moins aujourd'hui");
    }

    if (empty($data['heure_debut'])) {
        setFieldError('heure_debut', "L'heure de début est obligatoire");
    }

    if (empty($data['heure_fin'])) {
        setFieldError('heure_fin', "L'heure de fin est obligatoire");
    }

    if (
        !empty($data['heure_debut']) && !empty($data['heure_fin']) &&
        $data['heure_debut'] >= $data['heure_fin']
    ) {
        setFieldError('heure_fin', "L'heure de fin doit être après l'heure de début");
    }

    if (
        !empty($data['heure_debut']) && !empty($data['heure_fin']) &&
        (strtotime($data['heure_fin']) - strtotime($data['heure_debut'])) < 3600
    ) {
        setFieldError('heure_fin', "La durée minimale d'un cours est de 1 heure");
    }

    if (
        !empty($data['id_professeur']) && !empty($data['date_cours']) &&
        !empty($data['heure_debut']) && !empty($data['heure_fin']) &&
        !checkProfesseurDisponibilite(
            $data['id_professeur'],
            $data['date_cours'],
            $data['heure_debut'],
            $data['heure_fin'],
            $data['id_cours'] ?: null
        )
    ) {
        setFieldError('date_cours', "Le professeur a déjà un cours programmé à cette plage horaire");
    }

    if (
        !empty($data['salle']) && !empty($data['date_cours']) &&
        !empty($data['heure_debut']) && !empty($data['heure_fin']) &&
        !checkSalleDisponibilite(
            $data['salle'],
            $data['date_cours'],
            $data['heure_debut'],
            $data['heure_fin'],
            $data['id_cours'] ?: null
        )
    ) {
        setFieldError('salle', "La salle est déjà occupée à cette plage horaire");
    }

    if (empty($data['classes'])) {
        setFieldError('classes', "Vous devez sélectionner au moins une classe");
    }

    if (empty($data['salle'])) {
        setFieldError('salle', "Vous devez selectionnez une salle");
    }

    return empty(getFieldErrors());
}

function validateInscriptionEtudiant(array $data): bool
{
    $required = ['nom', 'prenom', 'email', 'password', 'matricule', 'id_classe', 'telephone', 'annee_scolaire', 'adresse'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            setFieldError($field, "Ce champ $field est obligatoire");
        }
    }
    if (emailExists($data['email'])) {
        setFieldError('email', "Cet email est déjà utilisé");
    }
    return empty(getFieldErrors());
}
