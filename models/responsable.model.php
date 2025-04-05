<?php
require_once ROOT_PATH . "/models/classe.model.php";
require_once ROOT_PATH . "/models/professeur.model.php";
require_once ROOT_PATH . "/models/cours.model.php";
require_once ROOT_PATH . "/models/absence.model.php";
require_once ROOT_PATH . "/models/filiere.model.php";
require_once ROOT_PATH . "/models/niveaux.model.php";
require_once ROOT_PATH . "/models/module.model.php";
require_once ROOT_PATH . "/models/semestre.model.php";

function getDashboardStats()
{
    return [
        'classes' => countClasses(),
        'professeurs' => countProfesseurs(),
        'cours' => countCoursThisMonth(),
    ];
}

function getCoursByFiliere()
{
    $sql = "SELECT f.libelle, COUNT(DISTINCT c.id_cours) as nb_cours
        FROM filieres f
        LEFT JOIN classes cl ON f.id_filiere = cl.id_filiere
        LEFT JOIN cours_classes cc ON cl.id_classe = cc.id_classe
        LEFT JOIN cours c ON cc.id_cours = c.id_cours
        GROUP BY f.id_filiere";
    return fetchResult($sql);
}
