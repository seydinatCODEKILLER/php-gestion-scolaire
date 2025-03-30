<?php

function countEtudiantsWithCriticalAbsences()
{
    $sql = "SELECT COUNT(DISTINCT e.id_etudiant) 
                       FROM etudiants e
                       JOIN absences a ON e.id_etudiant = a.id_etudiant
                       JOIN cours c ON a.id_cours = c.id_cours
                       LEFT JOIN justifications j ON a.id_absence = j.id_absence AND j.statut = 'acceptée'
                       WHERE j.id_justification IS NULL
                       GROUP BY e.id_etudiant
                       HAVING SUM(TIME_TO_SEC(TIMEDIFF(c.heure_fin, c.heure_debut))/3600 > 25";
    return fetchResult($sql, [], false);
}
