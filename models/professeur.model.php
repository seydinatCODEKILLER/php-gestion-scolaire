<?php
function countProfesseurs()
{
    $sql = "SELECT COUNT(*) nb_professeurs FROM professeurs";
    return fetchResult($sql, [], false);
}

function getAllProfesseurs($filters = [], $page = 1, $perPage = 10)
{
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar, u.state
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE 1=1";

    $params = [];

    if (!empty($filters['search'])) {
        $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR p.specialite LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
    }

    $sql .= " ORDER BY u.nom, u.prenom";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getProfesseurDetails(int $idProfesseur): array
{
    // Récupérer les infos de base du professeur
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar 
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE p.id_professeur = ?";

    $professeur = fetchResult($sql, [$idProfesseur], false);

    if (!$professeur) {
        return [];
    }

    // Récupérer les classes affectées
    $sqlClasses = "SELECT c.id_classe, c.libelle, c.annee_scolaire, 
                    f.libelle as filiere, n.libelle as niveau
                   FROM classes_professeur cp
                   JOIN classes c ON cp.id_classe = c.id_classe
                   JOIN filieres f ON c.id_filiere = f.id_filiere
                   JOIN niveaux n ON c.id_niveau = n.id_niveau
                   WHERE cp.id_professeur = ?";

    $classes = fetchResult($sqlClasses, [$idProfesseur]);

    return [
        'professeur' => $professeur,
        'classes' => $classes
    ];
}

function getProfesseurById($id)
{
    $sql = "SELECT p.*, u.nom, u.prenom, u.email, u.telephone, u.avatar, u.id_role
            FROM professeurs p
            JOIN utilisateurs u ON p.id_utilisateur = u.id_utilisateur
            WHERE p.id_professeur = ?";
    return fetchResult($sql, [$id], false);
}

function createProfesseur(array $data)
{
    // D'abord créer l'utilisateur
    $sqlUser = "INSERT INTO utilisateurs 
                (nom, prenom, email, password, id_role, avatar, telephone) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    $paramsUser = [
        $data['nom'],
        $data['prenom'],
        $data['email'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        2, // ID rôle professeur
        $data['avatar'],
        $data['telephone']
    ];

    // On demande explicitement le lastInsertId
    $userId = executeQuery($sqlUser, $paramsUser, true);

    if ($userId === false) {
        return false;
    }

    // Puis créer le professeur
    $sqlProf = "INSERT INTO professeurs 
                (id_utilisateur, specialite, grade, date_embauche) 
                VALUES (?, ?, ?, ?)";
    $paramsProf = [
        $userId,
        $data['specialite'],
        $data['grade'],
        date('Y-m-d')
    ];

    // On demande aussi le lastInsertId pour le professeur
    $profId = executeQuery($sqlProf, $paramsProf, true);

    if ($profId === false) {
        return false;
    }

    // Affecter les classes
    if (!empty($data['classes'])) {
        foreach ($data['classes'] as $idClasse) {
            $sqlAffect = "INSERT INTO classes_professeur 
                          (id_classe, id_professeur, date_affectation) 
                          VALUES (?, ?, NOW())";
            $success = executeQuery($sqlAffect, [$idClasse, $profId]);
            if (!$success) {
                return false;
            }
        }
    }

    return true;
}

function updateProfesseur(array $data): bool
{
    // Mettre à jour l'utilisateur
    $sqlUser = "UPDATE utilisateurs SET 
                nom = ?, prenom = ?, email = ?, telephone = ?, avatar = ?
                WHERE id_utilisateur = ?";
    $paramsUser = [
        $data['nom'],
        $data['prenom'],
        $data['email'],
        $data['telephone'],
        $data['avatar'],
        $data['id_utilisateur']
    ];

    $userUpdated = executeQuery($sqlUser, $paramsUser);
    if ($userUpdated === false) {
        return false;
    }

    // Mettre à jour le professeur
    $sqlProf = "UPDATE professeurs SET 
                specialite = ?, grade = ?
                WHERE id_professeur = ?";
    $paramsProf = [
        $data['specialite'],
        $data['grade'],
        $data['id_professeur']
    ];

    $profUpdated = executeQuery($sqlProf, $paramsProf);
    if ($profUpdated === false) {
        return false;
    }

    // Gestion des classes
    // 1. Supprimer toutes les affectations existantes
    $sqlDelete = "DELETE FROM classes_professeur WHERE id_professeur = ?";
    $deleted = executeQuery($sqlDelete, [$data['id_professeur']]);
    if ($deleted === false) {
        return false;
    }

    // 2. Ajouter les nouvelles affectations
    if (!empty($data['classes'])) {
        foreach ($data['classes'] as $idClasse) {
            $sqlInsert = "INSERT INTO classes_professeur 
                          (id_classe, id_professeur, date_affectation) 
                          VALUES (?, ?, NOW())";
            $inserted = executeQuery($sqlInsert, [$idClasse, $data['id_professeur']]);
            if ($inserted === false) {
                return false;
            }
        }
    }

    return true;
}

function toggleProfesseurStatus(int $idProfesseur, string $newStatus): bool
{
    $sql = "UPDATE utilisateurs SET state = ? WHERE id_utilisateur = 
            (SELECT id_utilisateur FROM professeurs WHERE id_professeur = ?)";
    return executeQuery($sql, [$newStatus, $idProfesseur]) !== false;
}

function getClassesByProfesseur(int $idProfesseur, string $annee = "")
{
    $sql = "SELECT c.*, f.libelle as filiere, n.libelle as niveau
            FROM classes_professeur cp
            JOIN classes c ON cp.id_classe = c.id_classe
            JOIN filieres f ON c.id_filiere = f.id_filiere
            JOIN niveaux n ON c.id_niveau = n.id_niveau
            WHERE cp.id_professeur = ?";

    $params = [$idProfesseur];

    if ($annee) {
        $sql .= " AND c.annee_scolaire = ?";
        $params[] = $annee;
    }

    return fetchResult($sql, $params);
}

function getProfesseurStats($idProfesseur)
{
    // Nombre total de cours
    $sql = "SELECT COUNT(*) as nb_cours FROM cours WHERE id_professeur = ?";
    $nbCours = fetchResult($sql, [$idProfesseur], false)['nb_cours'];

    // Heures enseignées
    $sqlHeures = "
    SELECT SUM(nombre_heures) as total_heures FROM cours 
    WHERE id_professeur = ? AND statut = 'effectué'
    ";
    $heures = fetchResult($sqlHeures, [$idProfesseur], false)['total_heures'] ?? 0;

    // Taux d'absence moyen
    $sqlTaux = "SELECT AVG(nb_absences/nb_etudiants)*100 as taux_absence
                FROM (
                    SELECT c.id_cours, 
                    COUNT(DISTINCT e.id_etudiant) as nb_etudiants,
                    COUNT(a.id_absence) as nb_absences
                    FROM cours c
                    JOIN cours_classes cc ON c.id_cours = cc.id_cours
                    JOIN inscriptions i ON cc.id_classe = i.id_classe
                    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
                    LEFT JOIN absences a ON (a.id_cours = c.id_cours AND a.id_etudiant = e.id_etudiant)
                    WHERE c.id_professeur = ?
                    GROUP BY c.id_cours
                ) as stats";
    $tauxAbsence = round(fetchResult($sqlTaux, [$idProfesseur], false)['taux_absence'] ?? 0, 2);

    return [
        'nb_cours' => $nbCours,
        'heures_enseignees' => $heures,
        'taux_absence' => $tauxAbsence
    ];
}

function getAbsencesByModule($idProfesseur)
{
    $sql = "SELECT m.libelle, COUNT(a.id_absence) as nb_absences,
            COUNT(DISTINCT c.id_cours) as nb_cours,
            COUNT(a.id_absence)/COUNT(DISTINCT c.id_cours) as moyenne_absences
            FROM modules m
            JOIN cours c ON m.id_module = c.id_module
            LEFT JOIN absences a ON c.id_cours = a.id_cours
            WHERE c.id_professeur = ?
            GROUP BY m.id_module
            ORDER BY nb_absences DESC";
    return fetchResult($sql, [$idProfesseur]);
}

function getTopAbsentStudents($idProfesseur, $limit = 5)
{
    $sql = "SELECT e.id_etudiant, u.nom, u.prenom, 
            COUNT(a.id_absence) as nb_absences,
            SUM(c.nombre_heures) as heures_manquees
            FROM etudiants e
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN inscriptions i ON e.id_etudiant = i.id_etudiant
            JOIN cours_classes cc ON i.id_classe = cc.id_classe
            JOIN cours c ON cc.id_cours = c.id_cours
            LEFT JOIN absences a ON (a.id_etudiant = e.id_etudiant AND a.id_cours = c.id_cours)
            WHERE c.id_professeur = ?
            GROUP BY e.id_etudiant
            ORDER BY nb_absences DESC
            LIMIT ?";
    return fetchResult($sql, [$idProfesseur, $limit]);
}

function getCoursByModule($idProfesseur)
{
    $sql = "SELECT 
                m.id_module,
                m.libelle,
                COUNT(c.id_cours) AS nb_cours,
                SUM(c.nombre_heures) AS total_heures,
                GROUP_CONCAT(DISTINCT cl.libelle SEPARATOR ', ') AS classes
            FROM modules m
            JOIN cours c ON m.id_module = c.id_module
            JOIN cours_classes cc ON c.id_cours = cc.id_cours
            JOIN classes cl ON cc.id_classe = cl.id_classe
            WHERE c.id_professeur = ?
            GROUP BY m.id_module
            ORDER BY nb_cours DESC";

    return fetchResult($sql, [$idProfesseur]);
}

function getCoursByProfesseurs(int $idProfesseur, array $filters = [], int $page = 1, int $perPage = 10): array
{
    $sql = "SELECT c.*, m.libelle as module_libelle,
            GROUP_CONCAT(cl.libelle SEPARATOR ', ') as classes_list
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            JOIN cours_classes cc ON c.id_cours = cc.id_cours
            JOIN classes cl ON cc.id_classe = cl.id_classe
            WHERE c.id_professeur = ?";

    $params = [$idProfesseur];

    // Application des filtres
    if (!empty($filters['statut'])) {
        $sql .= " AND c.statut = ?";
        $params[] = $filters['statut'];
    }

    if (!empty($filters['date_debut'])) {
        $sql .= " AND c.date_cours >= ?";
        $params[] = $filters['date_debut'];
    }

    if (!empty($filters['date_fin'])) {
        $sql .= " AND c.date_cours <= ?";
        $params[] = $filters['date_fin'];
    }

    if (!empty($filters['id_classe'])) {
        $sql .= " AND cc.id_classe = ?";
        $params[] = $filters['id_classe'];
    }

    // Group by pour éviter les doublons avec GROUP_CONCAT
    $sql .= " GROUP BY c.id_cours";
    $sql .= " ORDER BY c.date_cours DESC, c.heure_debut DESC";

    $result = paginateQuery($sql, $params, $page, $perPage);


    return $result;
}

function enregistrerAbsences(int $idCours, array $absentsIds): bool
{
    // 1. Supprimer les anciennes absences
    $sqlDelete = "DELETE FROM absences WHERE id_cours = ?";
    $deleted = executeQuery($sqlDelete, [$idCours]);
    if (!$deleted) return false;

    // 2. Ajouter les nouvelles absences
    if (!empty($absentsIds)) {
        $idProfesseur = getDataFromSession("user", "id_utilisateur");
        $sqlInsert = "INSERT INTO absences (id_etudiant, id_cours, date_absence, id_marqueur) 
                    VALUES (?, ?, CURDATE(), ?)";

        foreach ($absentsIds as $idEtudiant) {
            $inserted = executeQuery($sqlInsert, [$idEtudiant, $idCours, $idProfesseur]);
            if (!$inserted) return false;
        }
    }

    // 3. Mettre à jour le statut du cours
    $sqlUpdate = "UPDATE cours SET statut = 'effectué' WHERE id_cours = ?";
    $updated = executeQuery($sqlUpdate, [$idCours]);
    if (!$updated) return false;
    return true;
}

function getProchainCours(int $profId): ?array
{
    $sql = "SELECT c.*, m.libelle as module_libelle
            FROM cours c
            JOIN modules m ON c.id_module = m.id_module
            WHERE c.id_professeur = ? 
            AND c.date_cours >= CURDATE()
            AND c.statut = 'planifié'
            ORDER BY c.date_cours ASC, c.heure_debut ASC
            LIMIT 1";

    return fetchResult($sql, [$profId], false) ?: null;
}

function getRecentAbsences(int $profId, int $limit = 5): array
{
    $sql = "SELECT a.*, e.matricule, u.nom, u.prenom, m.libelle as module
            FROM absences a
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN cours c ON a.id_cours = c.id_cours
            JOIN modules m ON c.id_module = m.id_module
            WHERE c.id_professeur = ?
            ORDER BY a.date_absence DESC
            LIMIT ?";

    return fetchResult($sql, [$profId, $limit]);
}
