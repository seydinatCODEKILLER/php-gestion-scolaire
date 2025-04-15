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

/**
 * Récupère tous les cours d'un étudiant avec possibilité de filtrage
 * 
 * @param int $id_etudiant ID de l'étudiant
 * @param array $filters Tableau de filtres (date_debut, date_fin, id_semestre, statut)
 * @param int $page Numéro de page pour la pagination
 * @param int $perPage Nombre d'éléments par page
 * @return array Résultats paginés
 */
/**
 * Récupère les cours d'un étudiant pour l'année scolaire en cours
 * 
 * @param int $id_etudiant ID de l'étudiant
 * @param array $filters Filtres supplémentaires
 * @param int $page Numéro de page
 * @param int $perPage Nombre d'éléments par page
 * @return array Résultats paginés
 */
function getCoursEtudiantAnneeEnCours(int $id_etudiant, array $filters = [], int $page = 1, int $perPage = 3): array
{
    // 1. Récupérer l'année scolaire active
    $anneeEnCours = fetchResult(
        "SELECT id_annee FROM annee_scolaire WHERE est_active = TRUE LIMIT 1",
        [],
        false
    );

    if (!$anneeEnCours) {
        return ['data' => [], 'total' => 0];
    }

    // 2. Requête principale optimisée
    $sql = "SELECT 
                c.id_cours, c.date_cours, c.heure_debut, c.heure_fin,
                c.salle, c.statut, c.nombre_heures,
                m.libelle as module, m.code_module,
                CONCAT(u.prenom, ' ', u.nom) as professeur,
                p.specialite, u.avatar,
                s.libelle as semestre,
                cl.libelle as classe
            FROM inscriptions i
            JOIN classes cl ON i.id_classe = cl.id_classe
            JOIN cours_classes cc ON cc.id_classe = cl.id_classe
            JOIN cours c ON cc.id_cours = c.id_cours
            JOIN modules m ON c.id_module = m.id_module
            JOIN semestres s ON c.id_semestre = s.id_semestre
            JOIN professeurs p ON c.id_professeur = p.id_professeur
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE i.id_etudiant = ?
            AND cl.id_annee = ?";

    $params = [$id_etudiant, $anneeEnCours['id_annee']];

    // 3. Gestion dynamique des filtres
    $filterHandlers = [
        'date_debut' => fn($v) => [" AND c.date_cours >= ?", $v],
        'date_fin' => fn($v) => [" AND c.date_cours <= ?", $v],
        'semestre' => fn($v) => [" AND c.id_semestre = ?", $v],
        'statut' => fn($v) => [" AND c.statut = ?", $v],
    ];

    foreach ($filterHandlers as $key => $handler) {
        if (!empty($filters[$key])) {
            [$condition, $value] = $handler($filters[$key]);
            $sql .= $condition;
            $params[] = $value;
        }
    }

    // 4. Tri et pagination
    $sql .= " ORDER BY c.date_cours ASC, c.heure_debut ASC";

    return paginateQuery($sql, $params, $page, $perPage);
}
