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

function getCoursEffectuesParProfesseur(int $idProfesseur): array
{
    $sql = "SELECT c.id_cours, c.date_cours,c.heure_debut,c.heure_fin,c.statut, m.libelle as module_libelle,
            GROUP_CONCAT(cl.libelle SEPARATOR ', ') as classes_list
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN cours_classes cc ON c.id_cours = cc.id_cours
            JOIN classes cl ON cc.id_classe = cl.id_classe
            WHERE c.id_professeur = ? AND c.statut = 'effectué'
            GROUP BY c.id_cours
            ORDER BY c.date_cours DESC";

    return fetchResult($sql, [$idProfesseur]);
}

// function getEtudiantsPourAbsences(int $idCours): array
// {
//     $sql = "SELECT e.id_etudiant, e.matricule, 
//                    u.nom, u.prenom, u.avatar,
//                    cl.libelle as classe,
//                    IF(a.id_absence IS NULL, 0, 1) as absent
//             FROM cours_classes cc
//             JOIN inscriptions i ON cc.id_classe = i.id_classe
//             JOIN etudiants e ON i.id_etudiant = e.id_etudiant
//             JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
//             JOIN classes cl ON cc.id_classe = cl.id_classe
//             LEFT JOIN absences a ON (a.id_etudiant = e.id_etudiant AND a.id_cours = cc.id_cours)
//             WHERE cc.id_cours = ?
//             ORDER BY u.nom, u.prenom";
//     return fetchResult($sql, [$idCours]);
// }

// function enregistrerAbsencesSimplifie(int $idCours, array $absentsIds): bool
// {
//     // 1. Suppression des anciennes absences
//     $deleteSql = "DELETE FROM absences WHERE id_cours = ?";
//     executeQuery($deleteSql, [$idCours]);

//     // 2. Insertion des nouvelles absences
//     if (!empty($absentsIds)) {
//         $insertSql = "INSERT INTO absences (id_etudiant, id_cours, date_absence, id_marqueur)
//                      VALUES (?, ?, CURDATE(), ?)";
//         foreach ($absentsIds as $idEtudiant) {
//             executeQuery($insertSql, [$idEtudiant, $idCours, $_SESSION['user']['id_utilisateur']]);
//         }
//     }
//     return true;
// }

// function enregistrerAbsences(int $coursId, array $absentsIds): bool
// {
//     // 1. Suppression des anciennes absences
//     $deleteSql = "DELETE FROM absences WHERE id_cours = ?";
//     $deleted = executeQuery($deleteSql, [$coursId]);

//     if (!$deleted) return false;

//     // 2. Insertion des nouvelles absences
//     if (!empty($absentsIds)) {
//         $profId = getDataFromSession("user", "id_utilisateur");
//         $insertSql = "INSERT INTO absences (id_etudiant, id_cours, date_absence, id_marqueur) 
//                       VALUES (?, ?, CURDATE(), ?)";

//         foreach ($absentsIds as $idEtudiant) {
//             $inserted = executeQuery($insertSql, [$idEtudiant, $coursId, $profId]);
//             if (!$inserted) return false;
//         }
//     }

//     return true;
// }

function getEtudiantsPourAbsences(int $coursId): array
{
    $sql = "SELECT e.id_etudiant, e.matricule, 
                   u.nom, u.prenom, u.avatar,
                   cl.libelle as classe,
                   IF(a.id_absence IS NULL, 0, 1) as absent
            FROM cours_classes cc
            JOIN inscriptions i ON cc.id_classe = i.id_classe
            JOIN etudiants e ON i.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN classes cl ON cc.id_classe = cl.id_classe
            LEFT JOIN absences a ON (a.id_etudiant = e.id_etudiant AND a.id_cours = cc.id_cours)
            WHERE cc.id_cours = ?
            ORDER BY u.nom, u.prenom";

    return fetchResult($sql, [$coursId]);
}
