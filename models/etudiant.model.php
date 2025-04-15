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

/**
 * Récupère les absences non justifiées d'un étudiant
 * 
 * @param int $id_etudiant
 * @param array $filters (optionnel)
 * @return array
 */
function getAbsencesNonJustifiees(int $id_etudiant, array $filters = []): array
{
    $sql = "SELECT a.id_absence, a.date_absence, a.heure_marquage,
            c.heure_debut, c.heure_fin, c.salle,
            m.libelle as module, 
            CONCAT(u.prenom, ' ', u.nom) as professeur
            FROM absences a
            JOIN cours c ON a.id_cours = c.id_cours
            JOIN modules m ON c.id_module = m.id_module
            JOIN professeurs p ON c.id_professeur = p.id_professeur
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE a.id_etudiant = ?
            AND a.justified = 'en attente'";

    $params = [$id_etudiant];

    // Filtre par date
    if (!empty($filters['date_debut'])) {
        $sql .= " AND a.date_absence >= ?";
        $params[] = $filters['date_debut'];
    }

    if (!empty($filters['date_fin'])) {
        $sql .= " AND a.date_absence <= ?";
        $params[] = $filters['date_fin'];
    }

    $sql .= " ORDER BY a.date_absence DESC, c.heure_debut DESC";

    return fetchResult($sql, $params) ?: [];
}

/**
 * Enregistre une justification d'absence
 * 
 * @param int $id_absence
 * @param int $id_etudiant
 * @param string $motif
 * @param string|null $fichier (chemin du fichier)
 * @return bool
 */

function enregistrerJustification(int $id_absence, int $id_etudiant, string $motif, ?string $fichier = null): bool
{
    // 1. Enregistrer la justification
    $sqlJustification = "INSERT INTO justifications 
    (id_absence, id_etudiant, motif, pieces_jointes, date_justification) 
    VALUES (?, ?, ?, ?, NOW())";
    $paramsJustification = [$id_absence, $id_etudiant, $motif, $fichier];

    $resultJustification = executeQuery($sqlJustification, $paramsJustification);
    if (!$resultJustification) {
        return false;
    }

    // 2. Mettre à jour le statut de l'absence
    $sqlUpdateAbsence = "UPDATE absences SET justified = 'justifier' WHERE id_absence = ?";
    $paramsUpdate = [$id_absence];
    $resultUpdate = executeQuery($sqlUpdateAbsence, $paramsUpdate);
    return $resultUpdate !== false;
}


function getJustificationsByEtudiant(int $idEtudiant, array $filters = [], int $page = 1, int $perPage = 3): array
{
    $sql = "SELECT 
                j.id_justification,
                j.date_justification,
                j.motif,
                j.pieces_jointes,
                j.statut,
                j.commentaire_traitement,
                j.date_traitement,
                u.nom AS traitant_nom,
                u.prenom AS traitant_prenom,
                a.date_absence,
                c.date_cours,
                m.libelle AS module_libelle,
                CONCAT(p.nom, ' ', p.prenom) AS professeur_nom,
                p.avatar
            FROM 
                justifications j
            JOIN 
                absences a ON j.id_absence = a.id_absence
            JOIN 
                cours c ON a.id_cours = c.id_cours
            JOIN 
                modules m ON c.id_module = m.id_module
            JOIN 
                professeurs pr ON c.id_professeur = pr.id_professeur
            JOIN 
                utilisateurs p ON pr.id_utilisateur = p.id_utilisateur
            LEFT JOIN 
                utilisateurs u ON j.id_traitant = u.id_utilisateur
            WHERE 
                j.id_etudiant = ?";

    $params = [$idEtudiant];
    if (!empty($filters['statut'])) {
        $sql .= " AND j.statut = ?";
        $params[] = $filters['statut'];
    }


    $sql .= " ORDER BY j.date_justification DESC";
    return paginateQuery($sql, $params, $page, $perPage);
}

/**
 * Récupère une demande de justification par son ID
 * 
 * @param int $id_justification ID de la justification à récupérer
 * @return array|false Retourne les données de la justification ou false si non trouvée
 */
function getJustificationById(int $id_justification): array|false
{
    $sql = "SELECT 
                j.id_justification,
                j.id_absence,
                j.id_etudiant,
                j.date_justification,
                j.motif,
                j.pieces_jointes,
                j.statut,
                j.commentaire_traitement,
                j.date_traitement,
                j.id_traitant,
                u_traitant.nom AS traitant_nom,
                u_traitant.prenom AS traitant_prenom,
                a.date_absence,
                c.id_cours,
                c.date_cours,
                c.heure_debut,
                c.heure_fin,
                c.salle,
                m.id_module,
                m.libelle AS module_libelle,
                m.code_module,
                p.id_professeur,
                u_prof.nom AS professeur_nom,
                u_prof.prenom AS professeur_prenom,
                u_prof.avatar AS professeur_avatar,
                e.matricule,
                u_etud.nom AS etudiant_nom,
                u_etud.prenom AS etudiant_prenom,
                u_etud.avatar AS etudiant_avatar,
                cl.id_classe,
                cl.libelle AS classe_libelle
            FROM 
                justifications j
            JOIN 
                absences a ON j.id_absence = a.id_absence
            JOIN 
                cours c ON a.id_cours = c.id_cours
            JOIN 
                modules m ON c.id_module = m.id_module
            JOIN 
                professeurs p ON c.id_professeur = p.id_professeur
            JOIN 
                utilisateurs u_prof ON p.id_utilisateur = u_prof.id_utilisateur
            JOIN 
                etudiants e ON j.id_etudiant = e.id_etudiant
            JOIN 
                utilisateurs u_etud ON e.id_utilisateur = u_etud.id_utilisateur
            LEFT JOIN 
                utilisateurs u_traitant ON j.id_traitant = u_traitant.id_utilisateur
            LEFT JOIN
                classes cl ON e.id_classe = cl.id_classe
            WHERE 
                j.id_justification = ?";

    return fetchResult($sql, [$id_justification], false);
}
