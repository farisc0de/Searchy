<?php

include_once '../vendor/autoload.php';
include_once '../config.php';
include_once '../src/Database.php';
include_once '../src/SearchEngine.php';

$httpClient = new \GuzzleHttp\Client();

$file = fopen('urls.txt', "r");
$text = trim(fread($file, filesize('urls.txt')));
$urls = explode(PHP_EOL, $text);

$indexed_sites = [];

foreach ($urls as $url) {

    echo "Indexing {$url}" . PHP_EOL;

    $response = $httpClient->get($url);

    $htmlString = (string) $response->getBody();

    libxml_use_internal_errors(true);

    $htmlString = mb_convert_encoding($htmlString, 'HTML-ENTITIES', "UTF-8");

    $doc = new DOMDocument();

    $doc->loadHTML($htmlString);

    $xpath = new DOMXPath($doc);

    $titles = $xpath->evaluate('/html/head/title');
    $descriptions = $xpath->evaluate('/html/head/meta[@name="description"]/@content');
    $keywords = $xpath->evaluate('/html/head/meta[@name="keywords"]/@content');

    $title = '';

    $description = '';

    $keyword = '';

    foreach ($titles as $t) {
        $title = $t->textContent;
    }

    foreach ($descriptions as $d) {
        $description = $d->textContent;
    }

    foreach ($keywords as $k) {
        $keyword = $k->textContent;
    }

    if ($title == '') {
        $title = $url;
    }

    array_push($indexed_sites, [
        'title' => $title,
        'blurb' => $description,
        'keywords' => $keyword,
        'url' => $url
    ]);
}

$db = new Database($config);
$index = new SearchEngine($db);

$index->addSites($indexed_sites);

echo "Indexing Finished" . PHP_EOL;
