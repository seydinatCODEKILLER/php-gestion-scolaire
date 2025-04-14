<?php

/**
 * Récupère le nombre total de cours suivis par un étudiant
 * @param int $id_etudiant
 * @return int
 */
function getNombreCoursEtudiant(int $id_etudiant): int
{
    $sql = "SELECT COUNT(DISTINCT cc.id_cours) nbr_cours
            FROM cours_classes cc
            JOIN cours c ON cc.id_cours = c.id_cours
            JOIN inscriptions i ON cc.id_classe = i.id_classe
            WHERE i.id_etudiant = ? 
            AND c.date_cours <= CURDATE()";

    $result = fetchResult($sql, [$id_etudiant], false);
    return $result["nbr_cours"];
}

/**
 * Récupère le nombre total d'absences d'un étudiant
 * @param int $id_etudiant
 * @return int
 */
function getNombreAbsencesEtudiant(int $id_etudiant): int
{
    $sql = "SELECT COUNT(*) nbr_absences
            FROM absences 
            WHERE id_etudiant = ? 
            AND YEAR(date_absence) = YEAR(CURDATE())";

    $result = fetchResult($sql, [$id_etudiant], false);
    return $result["nbr_absences"];
}

/**
 * Récupère le nombre de justifications soumises par un étudiant
 * @param int $id_etudiant
 * @return int
 */
function getNombreJustificationsSoumises(int $id_etudiant): int
{
    $sql = "SELECT COUNT(*) nbr_justification
            FROM justifications 
            WHERE id_etudiant = ?";

    $result = fetchResult($sql, [$id_etudiant], false);
    return $result["nbr_justification"];
}

/**
 * Calcule le taux de présence d'un étudiant
 * @param int $id_etudiant
 * @return float
 */
function getTauxPresence(int $id_etudiant): float
{
    // Nombre total de cours
    $totalCours = getNombreCoursEtudiant($id_etudiant);
    if ($totalCours === 0) return 100.0;

    // Nombre d'absences non justifiées
    $sql = "SELECT COUNT(*) nbr_absence
            FROM absences a
            LEFT JOIN justifications j ON a.id_absence = j.id_absence AND j.statut = 'acceptée'
            WHERE a.id_etudiant = ? 
            AND j.id_justification IS NULL
            AND YEAR(a.date_absence) = YEAR(CURDATE())";

    $absencesNonJustifiees = fetchResult($sql, [$id_etudiant], false)["nbr_absence"];
    $absencesNonJustifiees = $absencesNonJustifiees ? (int)$absencesNonJustifiees[0] : 0;

    // Calcul du taux
    $presence = $totalCours - $absencesNonJustifiees;
    return round(($presence / $totalCours) * 100, 2);
}

/**
 * Récupère l'emploi du temps de l'étudiant pour la journée actuelle
 * @param int $id_etudiant
 * @return array
 */
function getEmploiDuTempsJournalier(int $id_etudiant): array
{
    $sql = "SELECT c.id_cours, m.libelle as module, 
                   c.heure_debut, c.heure_fin, 
                   c.salle, CONCAT(u.prenom, ' ', u.nom) as professeur
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN cours_classes cc ON c.id_cours = cc.id_cours
            JOIN inscriptions i ON cc.id_classe = i.id_classe
            JOIN professeurs p ON c.id_professeur = p.id_professeur
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE i.id_etudiant = ?
            AND c.date_cours = CURDATE()
            ORDER BY c.heure_debut ASC";

    return fetchResult($sql, [$id_etudiant]) ?: [];
}

/**
 * Récupère les informations de l'étudiant
 * @param int $id_etudiant
 * @return array
 */
function getInfosEtudiant(int $id_etudiant): array
{
    $sql = "SELECT e.*, u.nom, u.prenom, u.email, u.avatar, 
                   c.libelle as classe, f.libelle as filiere
            FROM etudiants e
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            LEFT JOIN classes c ON e.id_classe = c.id_classe
            LEFT JOIN filieres f ON c.id_filiere = f.id_filiere
            WHERE e.id_etudiant = ?";

    return fetchResult($sql, [$id_etudiant], false) ?: [];
}


function getIdEtudiantByIdUtilisateur(int $idUtilisateur): ?int
{
    $sql = "SELECT id_etudiant FROM etudiants WHERE id_utilisateur = ?";
    $result = fetchResult($sql, [$idUtilisateur], false);
    return $result ? (int) $result['id_etudiant'] : null;
}
