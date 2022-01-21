<?php
require_once('include/init.inc.php');

//echo '<pre>'; print_r($_SESSION); echo '</pre>';
if(!connect())
{
    header('location: connexion.php');
}

require_once('include/header.inc.php');
require_once('include/nav.inc.php');
?>

<h1 class="display-4 text-center" my-5> Bonjour <span class="text-success"><?= $_SESSION['user']['pseudo'] ?></span> </h1>
<div class="card col-md-5 mx-auto m-0 p-0 shadow-lg">
  <img src="https://picsum.photos/id/237/200/300" class="card-img-top" >
  <div class="card-body">
    <h5 class="card-title text-center">Vos informations personnelles</h5><hr>
    
    <?php 
    foreach($_SESSION['user'] as $key => $value): ?>

        <?php if($key!= 'id_membre' && $key != 'statut'): ?>

            <p class="card-text d-flex justify-content-between">
            <strong><?= ucfirst($key); ?></strong> 
            <span><?= $value ?> </span>

        <?php endif; ?>
    <?php endforeach; ?>

    <hr>
    <p class="card-text text-center">
    <a href="" class="btn btn-success">Modifier</a>

    </p>
  </div>
</div>
<?php

?>

<?php
require_once('include/footer.inc.php');