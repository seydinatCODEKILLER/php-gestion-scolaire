<?php
function countClasses()
{
    $sql = "SELECT COUNT(*) nb_classes FROM classes";
    return fetchResult($sql, [], false);
}

function getAllClasses()
{
    $sql = "SELECT c.*, f.libelle as filiere, n.libelle as niveau 
                    FROM classes c
                    JOIN filieres f ON c.id_filiere = f.id_filiere
                    JOIN niveaux n ON c.id_niveau = n.id_niveau";
    return fetchResult($sql);
}

function getClasseDetails($id_classe)
{
    $sql = "SELECT * FROM classes WHERE id_classe = :id_classe";
    $params = [":id_classe" => $id_classe];
    return fetchResult($sql, $params, false);
}
