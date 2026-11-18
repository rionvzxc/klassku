<?php
function PaginateList($db, $columns, $fromWhere, $orderBy, $params, $filters) {
    $pageSize = 60;
    $stmtCount = $db->Prepare("SELECT COUNT(*) ".$fromWhere, $params);
    $total = $stmtCount ? (int)$stmtCount->fetchColumn() : 0;
    $lastPage = max(1, (int)ceil($total / $pageSize));
    $requestedPage = filter_var($_GET["page"] ?? null, FILTER_VALIDATE_INT);
    $page = min($lastPage, max(1, $requestedPage ?: 1));
    $offset = ($page - 1) * $pageSize;
    $stmt = $db->Prepare(
        "SELECT ".$columns." ".$fromWhere." ORDER BY ".$orderBy." LIMIT ? OFFSET ?",
        array_merge($params, [$pageSize, $offset])
    );

    return [
        "items" => $stmt ? $stmt->fetchAll() : [],
        "page" => $page,
        "last_page" => $lastPage,
        "offset" => $offset,
        "filters" => array_filter($filters, fn($value) => $value !== "" && $value !== null)
    ];
}
