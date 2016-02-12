<?php

require __DIR__ . '/Register.php';

$registers = [
    'school',
    'industry',
];

foreach ($registers as $register) {
    $url = sprintf('http://%s.openregister.org/records.csv', $register);
    print "Fetching $url\n";

    $output = sprintf('data/%s.ndjson', $register);

    $client = new Register($url);
    $client->get(['page-size' => 5000], $output);
}
