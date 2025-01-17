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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['paiment_sucess'])): ?>
                alert("<?php echo $_SESSION['paiment_sucess']; ?>");
                <?php unset($_SESSION['paiment_sucess']); ?> // Supprimer le message après l'affichage
            <?php endif; ?>
        }
    </script>
</head>

<body>
    <?php genererNav(); ?>

    <div class="container">

        <div id="titre" class="mt-5 mb-5">
            <h1 class="h1">Articles de la 2nde Guerre Mondiale</h1>
        </div>

        <?php
        require_once('fonctions.php');

        $conn = connectionBD();
        mysqli_set_charset($conn, "utf8mb4");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Exécuter la requête
        $sql = "SELECT Article.id, Article.titre, Article.description, Article.descriptionLongue, Article.quantiteDispo, Article.prix, Image.chemin, Image.alt
            FROM Article
            LEFT JOIN Image ON Article.imageId = Image.id;";
        $result = $conn->query($sql);
        if (!$result) {
            die("Erreur lors de l'exécution de la requête : " . $conn->error);
        }

        if ($result->num_rows == 0) {
            echo "pas d'articles disponibles !";
        }
        ?>

        <div class="row">
            <?php foreach ($result as $article) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card text-decoration-none" style="width: 18rem; min-height: 250px; height: 100%; display: flex; flex-direction: column;">
                        <a>
                            <img src="<?= redimage($article['chemin'], 'vignettes/' . $article['titre'], 200, 200); ?>" alt="<?= $article['alt'] ?>" class="card-img-top p-2 rounded-top" data-bs-toggle="modal" data-bs-target="#fenetreModale-<?= $article['id'] ?>">
                        </a>
                        <div class="card-body d-flex flex-column justify-content-between" style="flex-grow: 1;">
                            <h5 class="card-title"><?= $article['titre'] ?></h5>
                            <p class="card-text"><?= $article['description'] ?></p>
                            <div class="d-flex justify-content-between ">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#fenetreModale-<?= $article['id'] ?>">Details</button>
                                <form method="post" action="index.php" class="d-md-inline-flex">
                                    <?php if ($article['quantiteDispo'] == 0) { ?>
                                        <button type="button" class="btn btn-danger" disabled>Indisponible</button>
                                    <?php } else { ?>
                                        <button type="submit" class="btn btn-primary" name="article_id" value="<?= $article['id'] ?>">Ajouter au panier</button>
                                    <?php } ?>
                                </form>
                            </div>

                        </div>
                        <div class="card-footer">
                            Prix : <?= $article['prix'] * 1 ?>€
                        </div>
                    </div>
                </div>


                <!-- Pop-up de détails -->
                <div class="modal fade" id="fenetreModale-<?= $article['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content shadow-lg">               
                            <div class="modal-header" style="background-color: #007bff; color: white;">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"><?= htmlspecialchars($article['titre']) ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="text-center mt-3">
                                <img src="<?= htmlspecialchars($article['chemin']) ?>" alt="<?= htmlspecialchars($article['alt']) ?>" class="img-fluid mb-3 card-img-top" style="max-height: 300px;"> <!-- Image responsive -->
                            </div>

                            <div class="modal-body">
                                <div>
                                    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($article['descriptionLongue'])) ?></p>
                                    <p class="mt-3"><strong>Prix:</strong> <?= htmlspecialchars($article['prix'] * 1) ?> €</p>
                                    <p><strong>Quantité disponible:</strong> <?= htmlspecialchars($article['quantiteDispo']) ?></p>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <form method="post" action="index.php">
                                    <?php if ($article['quantiteDispo'] == 0) { ?>
                                        <button type="button" class="btn btn-danger" disabled>Indisponible</button>
                                    <?php } else { ?>
                                        <button type="submit" class="btn btn-primary" name="article_id" value="<?= $article['id'] ?>">Ajouter au panier</button>
                                    <?php } ?>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <div id="toastAjoutPanier" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                Article ajouté au panier !
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>



            <?php
            }
            $conn->close();
            ?>

        </div>
    </div>

    <?php


    // Initialise le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }

    // Récupère l'ID de l'article depuis le formulaire (assure que c'est un entier)
    $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;

    // Vérifie que l'ID de l'article est valide
    if ($article_id > 0) {
        // Trouve l'index de l'articl   e dans le panier
        $article_index = trouverIndexDesArticles($_SESSION['panier'], $article_id);

        if ($article_index !== false) {
            // Si l'article existe, on augmente son nombre
            $_SESSION['panier'][$article_index][1] += 1;
        } else {
            $_SESSION['panier'][] = [$article_id, 1];
        }
    }
    ?>


    <?php genererFooter(); ?>
    <!-- Bootstrap 4.6.2 JS and dependencies (jQuery and Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>  
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_POST['article_id'])) { ?>
                var toastElement = document.getElementById('toastAjoutPanier');
                var toast = new bootstrap.Toast(toastElement);
                toast.show();

                // Cacher le toast après 2 secondes
                setTimeout(function () {
                    toast.hide();
                }, 2000);
            <?php } ?>
        });     
    </script>


</body>

</html>