<?php

function countClassesByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(*) as nb_classes 
            FROM classes_attaches 
            WHERE id_attache = ?";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['nb_classes'] : 0;
}


function countEtudiantsByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(DISTINCT e.id_etudiant) as nb_etudiants
            FROM classes_attaches ca
            JOIN classes c ON ca.id_classe = c.id_classe
            JOIN etudiants e ON e.id_classe = c.id_classe
            WHERE ca.id_attache = ?";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['nb_etudiants'] : 0;
}


function countJustificationsEnAttenteByAttache(int $idAttache): int
{
    $sql = "SELECT COUNT(*) as justifications_en_attente
            FROM justifications j
            JOIN absences a ON j.id_absence = a.id_absence
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN classes_attaches ca ON ca.id_classe = c.id_classe
            WHERE ca.id_attache = ? AND j.statut = 'en_attente'";
    $result = fetchResult($sql, [$idAttache], false);
    return $result ? (int) $result['justifications_en_attente'] : 0;
}

function getDernieresAbsencesByAttache(int $idAttache, int $limit = 5): array
{
    $sql = "SELECT a.date_absence, u.nom, u.prenom, c.libelle AS classe, m.libelle AS module
            FROM absences a
            JOIN etudiants e ON a.id_etudiant = e.id_etudiant
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            JOIN cours co ON a.id_cours = co.id_cours
            JOIN modules m ON co.id_module = m.id_module
            WHERE ca.id_attache = ?
            ORDER BY a.date_absence DESC
            LIMIT ?";

    return fetchResult($sql, [$idAttache, $limit]);
}

function getIdAttacheByIdUtilisateur(int $idUtilisateur): ?int
{
    $sql = "SELECT id_attache FROM attaches WHERE id_utilisateur = ?";
    $result = fetchResult($sql, [$idUtilisateur], false);
    return $result ? (int) $result['id_attache'] : null;
}


function getClassesWithStats(int $idAttache, array $filters = []): array
{
    $sql = "SELECT c.id_classe, c.libelle, c.annee_scolaire,
            COUNT(DISTINCT e.id_etudiant) as nb_etudiants,
            COUNT(DISTINCT a.id_absence) as nb_absences,
            f.libelle as filiere
            FROM classes c
            JOIN filieres f ON c.id_filiere = f.id_filiere
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            LEFT JOIN inscriptions i ON c.id_classe = i.id_classe
            LEFT JOIN etudiants e ON i.id_etudiant = e.id_etudiant
            LEFT JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            LEFT JOIN absences a ON e.id_etudiant = a.id_etudiant
            WHERE ca.id_attache = ?";

    $params = [$idAttache];

    if (!empty($filters['search'])) {
        $sql .= " AND (c.libelle LIKE ? OR f.libelle LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        array_push($params, $searchTerm, $searchTerm);
    }

    $sql .= " GROUP BY c.id_classe
            ORDER BY c.libelle";

    return fetchResult($sql, $params);
}

function getEtudiantsByAttache(int $idAttache, array $filters = [], int $page = 1, int $perPage = 10): array
{
    $sql = "SELECT e.id_etudiant, e.matricule, e.date_inscription,
            u.nom, u.prenom, u.email, u.telephone,u.state as statut,
            c.id_classe, c.libelle AS classe, 
            f.libelle AS filiere, 
            n.libelle AS niveau
            FROM etudiants e
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN classes c ON e.id_classe = c.id_classe
            JOIN filieres f ON c.id_filiere = f.id_filiere
            JOIN niveaux n ON c.id_niveau = n.id_niveau
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            WHERE ca.id_attache = ?";

    $where = [];
    $params = [$idAttache];

    // Filtre par classe
    if (!empty($filters['id_classe'])) {
        $where[] = "c.id_classe = ?";
        $params[] = $filters['id_classe'];
    }

    // Filtre par année scolaire
    if (!empty($filters['annee_scolaire'])) {
        $where[] = "c.annee_scolaire = ?";
        $params[] = $filters['annee_scolaire'];
    }

    // Filtre par statut de l'étudiant (disponible/archivé)
    if (!empty($filters['statut'])) {
        $where[] = "u.state = ?";
        $params[] = $filters['statut'];
    }

    // Filtre par nom ou prénom
    if (!empty($filters['search'])) {
        $where[] = "(u.nom LIKE ? OR u.prenom LIKE ? OR e.matricule LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }

    if (!empty($where)) {
        $sql .= " AND " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY u.nom ASC, u.prenom ASC";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getAbsencesByEtudiant(int $idEtudiant, array $filters = [], int $page = 1, int $perPage = 10): array
{
    $sql = "SELECT a.id_absence, a.date_absence, a.heure_marquage,
            cr.id_cours, cr.date_cours, cr.heure_debut, cr.heure_fin, cr.salle, cr.statut AS statut_cours,
            m.libelle AS module, 
            p.id_professeur, 
            CONCAT(u_prof.nom, ' ', u_prof.prenom) AS professeur,
            j.id_justification, j.statut AS statut_justification, j.motif,
            CONCAT(u_marqueur.nom, ' ', u_marqueur.prenom) AS marqueur
            FROM absences a
            JOIN cours cr ON a.id_cours = cr.id_cours
            JOIN modules m ON cr.id_module = m.id_module
            JOIN professeurs p ON cr.id_professeur = p.id_professeur
            JOIN utilisateurs u_prof ON p.id_utilisateur = u_prof.id_utilisateur
            LEFT JOIN utilisateurs u_marqueur ON a.id_marqueur = u_marqueur.id_utilisateur
            LEFT JOIN justifications j ON a.id_absence = j.id_absence AND j.id_etudiant = a.id_etudiant
            WHERE a.id_etudiant = ?";

    $where = [];
    $params = [$idEtudiant];

    // Filtre par date (intervalle)
    if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
        $where[] = "a.date_absence BETWEEN ? AND ?";
        $params[] = $filters['date_debut'];
        $params[] = $filters['date_fin'];
    }

    // Filtre par statut de justification
    if (!empty($filters['statut_justification'])) {
        $where[] = "j.statut = ?";
        $params[] = $filters['statut_justification'];
    }

    // Filtre par module
    if (!empty($filters['id_module'])) {
        $where[] = "m.id_module = ?";
        $params[] = $filters['id_module'];
    }

    // Filtre par statut du cours
    if (!empty($filters['statut_cours'])) {
        $where[] = "cr.statut = ?";
        $params[] = $filters['statut_cours'];
    }

    if (!empty($where)) {
        $sql .= " AND " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY a.date_absence DESC, cr.heure_debut DESC";

    return paginateQuery($sql, $params, $page, $perPage);
}

function getClassesByAttache(int $idAttache): array
{
    $sql = "SELECT c.id_classe, f.libelle filiere, c.libelle, c.annee_scolaire, c.capacite_max, c.state
            FROM classes c
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            JOIN filieres f ON f.id_filiere = c.id_filiere
            WHERE ca.id_attache = ?";
    $params = [$idAttache];
    return fetchResult($sql, $params);
}

function getEtudiantsInscrits(int $idAttache, array $filters): array
{
    $sql = "SELECT e.id_etudiant, e.matricule, u.nom, u.prenom, 
            c.libelle as classe, i.annee_scolaire, i.date_inscription
            FROM etudiants e
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            JOIN inscriptions i ON e.id_etudiant = i.id_etudiant
            JOIN classes c ON i.id_classe = c.id_classe
            JOIN classes_attaches ca ON c.id_classe = ca.id_classe
            WHERE ca.id_attache = ? AND u.state = 'disponible'";

    $params = [$idAttache];

    if (!empty($filters['id_classe'])) {
        $sql .= " AND c.id_classe = ?";
        $params[] = $filters['id_classe'];
    }

    if (!empty($filters['annee_scolaire'])) {
        $sql .= " AND i.annee_scolaire = ?";
        $params[] = $filters['annee_scolaire'];
    }

    if (!empty($filters['search'])) {
        $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR e.matricule LIKE ?)";
        $searchTerm = "%{$filters['search']}%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm);
    }

    $sql .= " ORDER BY u.nom, u.prenom";

    return paginateQuery($sql, $params);
}

function estEnPeriodeInscription(string $type = 'inscription'): bool
{
    // Récupération des dates de configuration depuis la base
    $config = fetchResult(
        "SELECT valeur FROM config WHERE cle = ?",
        ["periode_{$type}_debut"],
        false
    );

    $dateDebut = $config ? new DateTime($config['valeur']) : null;

    $config = fetchResult(
        "SELECT valeur FROM config WHERE cle = ?",
        ["periode_{$type}_fin"],
        false
    );

    $dateFin = $config ? new DateTime($config['valeur']) : null;

    // Si pas de configuration, période toujours ouverte
    if (!$dateDebut || !$dateFin) {
        return true;
    }

    $now = new DateTime();

    return $now >= $dateDebut && $now <= $dateFin;
}

function generateMatricule(): string
{
    // 1. Récupérer le dernier matricule utilisé depuis la base
    $dernierMatricule = fetchResult(
        "SELECT matricule FROM etudiants 
         WHERE matricule LIKE ? 
         ORDER BY id_etudiant DESC LIMIT 1",
        ['ET' . date('Y') . '%'],
        false
    );

    // 2. Déterminer le prochain numéro séquentiel
    $numero = 1; // Valeur par défaut si aucun matricule cette année

    if ($dernierMatricule) {
        // Extraire la partie numérique du dernier matricule
        $dernierNumero = (int)substr($dernierMatricule['matricule'], 6);
        $numero = $dernierNumero + 1;
    }

    // 3. Formater le numéro sur 4 chiffres avec leading zeros
    $numeroFormate = str_pad($numero, 4, '0', STR_PAD_LEFT);

    // 4. Construire le matricule complet
    return 'ET' . date('Y') . $numeroFormate;
}

function getPeriodeInscription(): array
{
    return fetchResult(
        "SELECT valeur as date_debut 
         FROM config 
         WHERE cle = 'periode_inscription_debut'",
        [],
        false
    ) ?: ['date_debut' => 'Non configuré'];
}

function getPeriodeReinscription(): array
{
    return fetchResult(
        "SELECT valeur as date_debut 
         FROM config 
         WHERE cle = 'periode_reinscription_debut'",
        [],
        false
    ) ?: ['date_debut' => 'Non configuré'];
}

function getDatesPeriodes(): array
{
    $periodes = fetchResult(
        "SELECT cle, valeur 
         FROM config 
         WHERE cle IN ('periode_inscription_debut', 'periode_inscription_fin',
        'periode_reinscription_debut', 'periode_reinscription_fin')"
    );

    $result = [
        'inscription' => ['debut' => 'Non configuré', 'fin' => 'Non configuré'],
        'reinscription' => ['debut' => 'Non configuré', 'fin' => 'Non configuré']
    ];

    foreach ($periodes as $periode) {
        if ($periode['cle'] === 'periode_inscription_debut') {
            $result['inscription']['debut'] = $periode['valeur'];
        } elseif ($periode['cle'] === 'periode_inscription_fin') {
            $result['inscription']['fin'] = $periode['valeur'];
        } elseif ($periode['cle'] === 'periode_reinscription_debut') {
            $result['reinscription']['debut'] = $periode['valeur'];
        } elseif ($periode['cle'] === 'periode_reinscription_fin') {
            $result['reinscription']['fin'] = $periode['valeur'];
        }
    }

    return $result;
}

function emailExists(string $email): bool
{
    $result = fetchResult(
        "SELECT COUNT(*) as count 
         FROM utilisateurs 
         WHERE email = ? AND state = 'disponible'",
        [$email],
        false
    );

    return $result && $result['count'] > 0;
}

function inscrireNouvelEtudiant(array $data): int|false
{
    $userId = executeQuery(
        "INSERT INTO utilisateurs (nom, prenom, email, password, id_role,avatar, telephone, adresse, state)
         VALUES (?, ?, ?, ?, 4, ?, ?,?, 'disponible')",
        [
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['password'],
            $data['avatar'],
            $data['telephone'],
            $data['adresse']
        ],
        true
    );

    if (!$userId) return false;

    // 2. Création de l'étudiant
    $etudiantId = executeQuery(
        "INSERT INTO etudiants (id_utilisateur, matricule, date_inscription, id_classe)
         VALUES (?, ?, CURDATE(), ?)",
        [$userId, $data['matricule'], $data['id_classe']],
        true
    );

    if (!$etudiantId) return false;

    // 3. Création de l'inscription
    $inscriptionId = executeQuery(
        "INSERT INTO inscriptions (id_etudiant, id_classe, annee_scolaire, date_inscription, statut, est_reinscription)
         VALUES (?, ?, ?, CURDATE(), 'validée', 0)",
        [$etudiantId, $data['id_classe'], $data['annee_scolaire']],
        true
    );

    return $inscriptionId ? $etudiantId : false;
}

function reinscrireEtudiant(int $idEtudiant, int $idClasse, string $anneeScolaire, bool $isRedoublement): bool
{
    $existingInscription = getExistingInscription($idEtudiant, $anneeScolaire);

    if ($existingInscription) {
        if (!updateExistingInscription($existingInscription['id_inscription'], $idClasse, $isRedoublement)) {
            return false;
        }
    } else {
        if (!createNewInscription($idEtudiant, $idClasse, $anneeScolaire, $isRedoublement)) {
            return false;
        }
    }
    return true;
}

function getExistingInscription(int $idEtudiant, string $anneeScolaire): ?array
{
    return fetchResult(
        "SELECT id_inscription FROM inscriptions 
         WHERE id_etudiant = ? AND annee_scolaire = ?",
        [$idEtudiant, $anneeScolaire],
        false
    );
}

function updateExistingInscription(int $idInscription, int $idClasse, bool $isRedoublement): bool
{
    $stmt = executeQuery(
        "UPDATE inscriptions SET 
         id_classe = ?, 
         redoublement = ?,
         statut = 'validée',
         date_inscription = NOW()
         WHERE id_inscription = ?",
        [$idClasse, $isRedoublement ? 1 : 0, $idInscription]
    );

    return $stmt !== false && $stmt->rowCount() > 0;
}

function createNewInscription(int $idEtudiant, int $idClasse, string $anneeScolaire, bool $isRedoublement): bool
{
    executeQuery(
        "UPDATE inscriptions SET statut = 'validée'
         WHERE id_etudiant = ?",
        [$idEtudiant]
    );

    // Créer nouvelle inscription
    return executeQuery(
        "INSERT INTO inscriptions 
         (id_etudiant, id_classe, annee_scolaire, date_inscription, redoublement, statut)
         VALUES (?, ?, ?, NOW(), ?, 'validée')",
        [$idEtudiant, $idClasse, $anneeScolaire, $isRedoublement ? 1 : 0]
    );
}
