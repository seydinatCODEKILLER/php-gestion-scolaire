<?php
function formatDate(string $date): string
{
    if ($date === 'Non configuré') {
        return $date;
    }

    try {
        $dateObj = new DateTime($date);
        return $dateObj->format('d/m/Y');
    } catch (Exception $e) {
        return $date;
    }
}

function getAnneeScolaireActuelle(): string
{
    $anneeCourante = (int)date('Y');
    $moisCourant = (int)date('m');

    // Si on est après août (mois >= 9), on est dans la nouvelle année scolaire
    if ($moisCourant >= 9) {
        return $anneeCourante . '-' . ($anneeCourante + 1);
    }

    // Sinon, on est encore dans l'année scolaire précédente
    return ($anneeCourante - 1) . '-' . $anneeCourante;
}

function getAnneesScolaires(int $nbAnnees = 5): array
{
    $annees = [];
    $anneeActuelle = getAnneeScolaireActuelle();
    $anneeReference = (int)explode('-', $anneeActuelle)[0];

    for ($i = $nbAnnees; $i >= 1; $i--) {
        $anneeDebut = $anneeReference - $i + 1;
        $annees[] = $anneeDebut . '-' . ($anneeDebut + 1);
    }

    // Ajoute l'année en cours si elle n'est pas déjà incluse
    if (!in_array($anneeActuelle, $annees)) {
        $annees[] = $anneeActuelle;
    }

    return array_reverse($annees); // Du plus ancien au plus récent
}
