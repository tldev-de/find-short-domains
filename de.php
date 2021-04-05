<?php
require(__DIR__ . '/vendor/autoload.php');

define('DE_CANDIDATES_FILE', 'de_candidates.txt');

$allowedChars = array_merge(
    range('a', 'z'),
    range(0, 9),
    ['-']
);

// generate all three chars candidates
if (!is_file(DE_CANDIDATES_FILE)) {
    foreach ($allowedChars as $char1) {
        foreach ($allowedChars as $char2) {
            foreach ($allowedChars as $char3) {
                $domain = $char1 . $char2 . $char3 . '.de';
                if (preg_match('/^[a-z0-9][1-z0-9\-]+[a-z0-9]\.de$/', $domain)) {
                    file_put_contents(DE_CANDIDATES_FILE, $domain . "\n", FILE_APPEND);
                }
            }
        }
    }
}

// check candidates for availability
$candidates = file(DE_CANDIDATES_FILE);
$whois = new Whois();
$whois->deepWhois = false; // query only one whois server
foreach ($candidates as $key => $candidate) {
    $candidate = trim($candidate); // remove newline
    echo 'next candidate: ' . $candidate . ' - ' . ($key + 1) . ' / ' . count($candidates) . PHP_EOL;

    // since whois queries are very slow we use dns queries to speed things up ;)
    if (gethostbyname($candidate) !== $candidate) {
        echo '... skip ' . $candidate . ' due to dns' . PHP_EOL;
        usleep(50000);
        continue;
    }

    $result = $whois->lookup($candidate, false);
    if ($result['regrinfo']['registered'] !== 'no') {
        echo '... skip ' . $candidate . ' due to whois' . PHP_EOL;
        usleep(500000);
        continue;
    }

    file_put_contents('free_de_domains.txt', $candidate . PHP_EOL, FILE_APPEND);
}