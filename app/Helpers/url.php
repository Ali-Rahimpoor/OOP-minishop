<?php

function site_url(string $path = ''): string
{
    return htmle( 'http://localhost/oop-minishop/' . $path );
}
function sortLinks(string $column, string $label,array $filters):string
{
    $currentSort = $filters['sort'] ?? '';

    $currentDir  = $filters['dir'] ?? 'desc';    
    $newDir = ($currentSort === $column && $currentDir === 'desc') ? 'asc' : 'desc';

    $query = array_merge($filters, [
        'sort' => $column,
        'dir'  => $newDir,
    ]);

    $url = '?' . http_build_query($query);

    // نمایش فلش برای ستونی که الان مرتب شده
    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentDir === 'desc' ? ' ▼' : ' ▲';
    }

    return '<a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($label) . $arrow . '</a>';
}
function pageLink(int $page, array $filters):string
{
    $query = array_merge($filters,['page'=>$page]);
    return '?' . http_build_query($query);
}