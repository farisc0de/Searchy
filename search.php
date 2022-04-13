<?php

include_once 'config.php';
include_once 'src/Database.php';
include_once 'src/SearchEngine.php';

if (!isset($_GET['k'])) {
    header('Location: index.php');
}

$keyword = $_GET['k'];

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$db = new Database($config);
$se = new SearchEngine($db);

$sites = $se->findSites($keyword, $page);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searchy - <?php echo $keyword; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </script>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="pt-3">
            <form class="d-flex" method="GET" action="search.php">
                <input class="form-control me-sm-2" type="text" name="k" value="<?= $_GET['k']; ?>" placeholder="Search">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>

        <div class="pt-4">
            <div class="d-flex justify-content-between">
                <p>You searched for <b>"<?php echo $keyword ?>"</b></p>

                <p><b><?php echo $sites['count']; ?></b> results found</p>
            </div>
        </div>

        <hr />

        <div class="pt-3">
            <ul>
                <?php foreach ($sites['results'] as $site) : ?>

                    <li>
                        <a href="<?php echo $site->url ?>">
                            <h3><?php echo $site->title ?></h3>
                        </a>
                        <p><?php echo $site->blurb ?></p>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>

        <div class="pt-5">
            <ul class="pagination pagination-sm">
                <?php for ($page = 1; $page <= $sites['pages']; $page++) : ?>
                    <li class="page-item <?php echo isset($_GET['page']) && $_GET['page'] == $page ? 'active' : '' ?>">
                        <a class="page-link" href="search.php?k=<?= $keyword ?>&page=<?= $page ?>"><?= $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>