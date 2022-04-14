<?php

include_once 'vendor/autoload.php';
include_once 'config.php';
include_once 'src/Database.php';
include_once 'src/SearchEngine.php';

$httpClient = new \GuzzleHttp\Client();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = $_POST['url'];

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

    $site = [
        'title' => $title,
        'blurb' => $description,
        'keywords' => $keyword,
        'url' => $url
    ];

    $db = new Database($config);

    $index = new SearchEngine($db);

    $added = $index->addSite($site);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searchy - Crawl a Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Searchy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Add Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="crawl.php">Crawl Site</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($added) && $added == false) : ?>
            <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-xmark"></i> Site already exist
                </div>
            </div>
        <?php elseif (isset($added) && $added == true) : ?>
            <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> Site Added
                </div>
            </div>
        <?php else : ?>
        <?php endif; ?>
        <div class="d-flex align-items-center justify-content-center pt-3">
            <div class="card border-dark mb-3" style="max-width: 40rem;">
                <div class="card-header">Crawl a Site</div>
                <div class="card-body">
                    <form class="d-flex" method="POST">
                        <input class="form-control me-sm-2" type="url" name="url" placeholder="URL...">
                        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>