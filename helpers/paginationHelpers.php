<?php

/**
 * Génère les données de pagination et applique le LIMIT à la requête
 * 
 * @param string $baseSql - Requête SQL de base (sans le LIMIT)
 * @param array $params - Paramètres pour la requête préparée
 * @param int $currentPage - Page actuelle
 * @param int $perPage - Nombre d'éléments par page
 * @return array - [data, pagination]
 */
function paginateQuery(string $baseSql, array $params = [], int $currentPage = 1, int $perPage = 10): array
{
    // Valider les entrées
    $currentPage = max(1, $currentPage);
    $perPage = max(1, $perPage);

    // Compter le nombre total d'éléments
    $countSql = "SELECT COUNT(*) FROM ($baseSql) AS total";
    $totalItems = fetchResult($countSql, $params, false);

    // Calculer les infos de pagination
    $totalPages = max(1, ceil($totalItems['COUNT(*)'] / $perPage));
    $currentPage = min($currentPage, $totalPages);
    $offset = ($currentPage - 1) * $perPage;

    // Ajouter le LIMIT à la requête principale
    $paginatedSql = "$baseSql LIMIT ? OFFSET ?";
    $paginatedParams = array_merge($params, [(int)$perPage, (int)$offset]);

    // Exécuter la requête paginée
    $data = fetchResult($paginatedSql, $paginatedParams);

    return [
        'data' => $data,
        'pagination' => [
            'totalItems' => $totalItems['COUNT(*)'],
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $totalItems)
        ]
    ];
}

/**
 * Génère un lien de pagination
 */
function generatePageLink(array $queryParams, int $page): string
{
    return '?' . http_build_query(array_merge($queryParams, ['p' => $page]));
}

/**
 * Génère le texte d'information "Affichage de X à Y sur Z"
 */
function renderPaginationInfo(array $pagination): string
{
    return sprintf(
        'Affichage de <span class="font-medium">%d</span> à <span class="font-medium">%d</span> sur <span class="font-medium">%d</span> résultats',
        $pagination['from'],
        $pagination['to'],
        $pagination['totalItems']
    );
}

/**
 * Génère les boutons mobile (Précédent/Suivant)
 */
function renderMobilePagination(array $pagination, array $queryParams): string
{
    $current = $pagination['currentPage'];
    $html = '';

    if ($current > 1) {
        $html .= sprintf(
            '<a href="%s" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Précédent</a>',
            generatePageLink($queryParams, $current - 1)
        );
    }

    if ($current < $pagination['totalPages']) {
        $html .= sprintf(
            '<a href="%s" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Suivant</a>',
            generatePageLink($queryParams, $current + 1)
        );
    }

    return $html ? '<div class="flex-1 flex justify-between sm:hidden">' . $html . '</div>' : '';
}

/**
 * Génère le bouton Précédent
 */
function renderPreviousButton(int $currentPage, array $queryParams): string
{
    if ($currentPage <= 1) return '';

    return sprintf(
        '<a href="%s" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Précédent</span>
            <i class="ri-arrow-left-s-line"></i>
        </a>',
        generatePageLink($queryParams, $currentPage - 1)
    );
}

/**
 * Génère le bouton Suivant
 */
function renderNextButton(int $currentPage, int $totalPages, array $queryParams): string
{
    if ($currentPage >= $totalPages) return '';

    return sprintf(
        '<a href="%s" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Suivant</span>
            <i class="ri-arrow-right-s-line"></i>
        </a>',
        generatePageLink($queryParams, $currentPage + 1)
    );
}

/**
 * Génère les numéros de page
 */
function renderPageNumbers(array $pagination, array $queryParams): string
{
    $current = $pagination['currentPage'];
    $total = $pagination['totalPages'];
    $start = max(1, $current - 2);
    $end = min($total, $current + 2);
    $html = '';

    // Première page + ellipsis si nécessaire
    if ($start > 1) {
        $html .= renderPageLink(1, $queryParams, false);
        if ($start > 2) {
            $html .= '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
        }
    }

    // Pages centrales
    for ($i = $start; $i <= $end; $i++) {
        $html .= renderPageLink($i, $queryParams, $i === $current);
    }

    // Dernière page + ellipsis si nécessaire
    if ($end < $total) {
        if ($end < $total - 1) {
            $html .= '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
        }
        $html .= renderPageLink($total, $queryParams, false);
    }

    return $html;
}

/**
 * Génère un lien de page individuel
 */
function renderPageLink(int $page, array $queryParams, bool $isActive): string
{
    $classes = $isActive
        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50';

    return sprintf(
        '<a href="%s" class="%s relative inline-flex items-center px-4 py-2 border text-sm font-medium">%d</a>',
        generatePageLink($queryParams, $page),
        $classes,
        $page
    );
}

function renderPagination(array $pagination, array $queryParams = []): string
{
    $mobilePagination = renderMobilePagination($pagination, $queryParams);
    $paginationInfo = renderPaginationInfo($pagination);
    $previousButton = renderPreviousButton($pagination['currentPage'], $queryParams);
    $nextButton = renderNextButton(
        $pagination['currentPage'],
        $pagination['totalPages'],
        $queryParams
    );
    $pageNumbers = renderPageNumbers($pagination, $queryParams);

    return <<<HTML
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        {$mobilePagination}
        
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">{$paginationInfo}</p>
            </div>
            
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {$previousButton}
                    {$pageNumbers}
                    {$nextButton}
                </nav>
            </div>
        </div>
    </div>
    HTML;
}
