<?php
function DateF($date, $format = "dd MMMM yyyy", $locale = "id_ID", $timezone = "Asia/Jakarta") {
    $formatter = new IntlDateFormatter(
        $locale,
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE,
        $timezone,
        IntlDateFormatter::GREGORIAN,
        $format
    );
    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return $date;
    }
    $result = $formatter->format($timestamp);
    return $result !== false ? $result : $date;
}
?>