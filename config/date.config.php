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
