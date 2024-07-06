<?php

// Returns a relative time to or from the given date/time.
// Example: If $date = '2019-04-11 09:30' -> "2 days ago"
// Borrowed from http://us.php.net/manual/en/function.time.php#89415
function NiceTime($date)
{
    if (empty($date)) {
        return "";
    }

    // Can set timezone if not wanting to use default
    // date_default_timezone_set('America/Chicago');

    $periods = ["second", "minute", "hour", "day", "week", "month", "year", "decade"];
    $lengths = ["60", "60", "24", "7", "4.35", "12", "10"];

    $now = time();
    $unix_date = strtotime($date);

    // Check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }

    // Is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    if ($difference == 0) {
        return "just now";
    }

    return "$difference $periods[$j] {$tense}";
}

?>
