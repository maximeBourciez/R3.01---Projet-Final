<?php
    session_start();
    require_once('fonctions.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>War.net | Vente de matériel militaire</title>
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.css">
</head>
<body>
    <?php genererNav(); ?>

    <div class="container">

        <div id="titre" class="mt-5 mb-5">
            <h1 class="h1">Articles de la 2nde Guerre Mondiale</h1>
        </div>

        <?php 
            require_once('fonctions.php');

            $conn = connectionBDLocalhost();
            mysqli_set_charset($conn, "utf8mb4");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Exécuter la requête
            $sql = "SELECT Article.id, Article.titre, Article.description, Article.prix, Image.chemin, Image.alt, Categorie.nom AS categorie
            FROM Article
            LEFT JOIN Image ON Article.imageId = Image.id
            LEFT JOIN Categorie ON Article.categorieId = Categorie.id;
            ";
            $result = $conn->query($sql);
            if (!$result) {
                die("Erreur lors de l'exécution de la requête : " . $conn->error);
            }

            if ($result->num_rows == 0) {
                echo "pas d'articles disponibles !";
            } 
        ?>

        <div class="row">
            <?php foreach($result as $article) { ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem; min-height: 250px;">
                    <img src="<?=redimage($article['chemin'], 'vignettes/' . $article['titre'], 200, 200);?>" class="card-img-top" alt="<?= $article['alt'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article['titre'] ?></h5>
                        <p class="card-text"><?= $article['description'] ?></p>
                        <a href="#" class="btn btn-primary">Ajouter au panier</a>
                        <a href="#" class="btn btn-secondary">Details</a>
                    </div>
                    <div class="card-footer">
                        Prix : <?= $article['prix'] ?>€
                    </div>
                </div>
            </div>
            <?php 
            }
            $conn->close(); 
        ?>
        </div>

        
    </div>


    <footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>war.net</h5>
                <p>Le site de vente en ligne de matériel historique de la Seconde Guerre mondiale.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens Utiles</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white">Accueil</a></li>
                    <li><a href="panier.php" class="text-white">Panier</a></li>
                    <li><a href="login.html" class="text-white">Se connecter</a></li>
                    <li><a href="contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Suivez-nous</h5>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i> Facebook</a><br>
                <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i> Twitter</a><br>
                <a href="#" class="text-white"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <p>&copy; 2024 war.net. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>



    <script src="js/script.js"></script>
</body>
</html>

