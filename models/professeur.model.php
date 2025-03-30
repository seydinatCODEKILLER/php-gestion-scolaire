<?php
function countProfesseurs()
{
    $sql = "SELECT COUNT(*) nb_professeurs FROM professeurs";
    return fetchResult($sql, [], false);
}

function getAllProfesseurs()
{
    $sql = "SELECT p.*, u.nom, u.prenom, u.email
                    FROM professeurs p
                    JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur";
    return fetchResult($sql);
}
