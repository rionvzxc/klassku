<?php
function SaveAgendaOnUniqueDate($db, $idUser, $idClass, $date, $idAgenda, $save) {
    if (!is_string($date) || !preg_match("/^([1-9][0-9]{3})-([0-9]{2})-([0-9]{2})$/D", $date, $parts)
        || !checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1])) {
        return "invalid-date";
    }
    if (!$db->BeginTransaction()) {
        return "failed";
    }
    try {
        // Serialize agenda writes for this class, including when the date has no row yet.
        $stmtClass = $db->Prepare(
            "SELECT id FROM class WHERE id = ? AND id_user = ? AND is_active = 1 FOR UPDATE",
            [$idClass, $idUser]
        );

        if (!$stmtClass || !$stmtClass->fetchColumn()) {
            return "failed";
        }
        $stmtDate = $db->Prepare(
            "SELECT id FROM agenda WHERE id_class = ? AND date = ? AND id <> ? LIMIT 1",
            [$idClass, $date, $idAgenda]
        );

        if (!$stmtDate) {
            return "failed";
        }
        if ($stmtDate->fetchColumn() !== false) {
            return "duplicate-date";
        }
        return $save() && $db->Commit() ? "" : "failed";
    } finally {
        if ($db->InTransaction()) {
            $db->RollBack();
        }
    }
}
