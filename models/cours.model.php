<?php
function countCoursThisMonth()
{
    $sql = "SELECT COUNT(*) nb_cours FROM cours WHERE MONTH(date_cours) = MONTH(CURRENT_DATE())";
    return fetchResult($sql, [], false);
}

function getCoursByProfesseur($id_professeur)
{
    $sql = "SELECT * FROM cours WHERE id_professeur = :id_professeur";
    $params = [":id_professeur" => $id_professeur];
    return fetchResult($sql, $params);
}
