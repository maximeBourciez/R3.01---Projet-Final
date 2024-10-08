<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente en ligne</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="index.php">Hidden brand</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Panier</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li> -->
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <a href="login.html" class="btn btn-primary ms-3" tabindex="-1" role="button" aria-disabled="true">Se connecter</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="width: 18rem;">
            <img src="..." class="card-img-top" alt="Image indisponible">
            <div class="card-body">
                <h5 class="card-title">Titre</h5>
                <p class="card-text">Feur.</p>
                <a href="#" class="btn btn-primary">Ajouter au panier</a>
            </div>
            <div class="card-footer">
                Prix : 10 000€
            </div>
        </div>
    </div>


    <script src="js/script.js"></script>
</body>
</html>
