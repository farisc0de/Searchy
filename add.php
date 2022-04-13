<?php

include_once 'config.php';
include_once 'src/Database.php';
include_once 'src/SearchEngine.php';

$db = new Database($config);
$se = new SearchEngine($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$se->checkIfSiteExist($_POST['url'])) {
        $se->addSite([
            'title' => $_POST['title'],
            'blurb' => $_POST['blurb'],
            'keywords' => $_POST['keywords'],
            'url' => $_POST['url']
        ]);
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searchy - Home</title>
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
                        <a class="nav-link active" href="add.php">Add Site</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if ($error) : ?>
            <div class="d-flex align-items-center justify-content-center mt-3">
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-xmark"></i> Site already exist
                </div>
            </div>
        <?php endif; ?>
        <div class="d-flex align-items-center justify-content-center pt-3">
            <div class="card border-dark mb-3" style="max-width: 20rem;">
                <div class="card-header">Add a Site</div>
                <div class="card-body">
                    <form method="POST">
                        <input class="form-control me-sm-2" type="text" name="title" placeholder="Website Title">
                        <textarea placeholder="Website Decription" name="blurb" class="form-control mt-3" cols="20" rows="5"></textarea>
                        <input class="form-control mt-3" type="text" name="keywords" placeholder="Website Keywords" />
                        <input type="url" name="url" class="form-control mt-3 mb-3" placeholder="Website URL">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>

</html>