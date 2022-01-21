<?php 
require_once('include/init.inc.php');
require_once('include/header.inc.php');
require_once('include/nav.inc.php');
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

    <div class="col-lg-3">

        <h4 class="my-4 text-center">Que du lourd !! Viendez voir !!</h4>

        <!-- Faites en sorte de réaliser le traitement PHP/SQL permettant d'afficher les catégories de la BDD (éliminer les doublons) | SELECT + FETCH + BOUCLE | faites en sorte en cliquant sur les liens de transmettre la catégorie dans l'URL (categorie=tee-shirt) -->

        <?php 
        $data = $bdd->query("SELECT DISTINCT(categorie) FROM produit");
        ?>

        <div class="list-group">
            <p class="list-group-item bg-success text-center text-white p-0 m-0">CATEGORIES</p>

        <?php while($cat = $data->fetch(PDO::FETCH_ASSOC)): 
            
            // echo '<pre>'; print_r($cat); echo '</pre>';
            ?>

            <a href="?categorie=<?= $cat['categorie'] ?>" class="list-group-item text-center text-dark"><?= $cat['categorie'] ?></a>

        <?php endwhile; ?> 
        </div>

    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                    <img class="d-block img-fluid" src="<?= URL ?>photo/slider1.png" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="<?= URL ?>photo/slider2.jpeg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="<?= URL ?>photo/slider3.jpg" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <div class="row">

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
                <div class="card-body">
                    <h4 class="card-title">
                    <a href="#">Item One</a>
                    </h4>
                    <h5>$24.99</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!</p>
                </div>
                <div class="card-footer">
                    <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                </div>
                </div>
            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<?php 
require_once('include/footer.inc.php');