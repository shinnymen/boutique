<?php
require_once('../include/init.inc.php');

echo '<pre>'; print_r($_POST); echo '</pre>';

// Toutes les informations liees a un fichier uploade est directement stocke dans la superglobale $_FILES en PHP
echo '<pre>'; print_r($_FILES); echo '</pre>';

// Si l'internaute n;est pas administrateur, cela veut dire que la session son statut n'est pas 'admin', ou alors l'internaute n'est meme pas identifie, il n'a rien a faire sur la page gestion_boutique, on le redirige vers l'authentification
if(!adminConnect()){
    header('location: ' . URL . 'connexion.php');
}

// SUPPRESSION PRODUIT
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    echo '<h1 class="display-4 text-center my-5">Affichage des produits</h1>';

 
    // echo 'je veux supprimer le produit';

    // Exo: realiser le traitement PHP/SQL permettant de supprimer le produit en BDD en fonction de l'id_produit transmit dans l'URL (prepare + BindValue + execute + DELETE)
   
    $validation = $bdd->prepare( "DELETE FROM produit WHERE id_produit = VALUES :id_produit");
    $validation->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $validation->execute();
    
    // Apres avoir supprime le produit, dans l'URL est stocke 'action=suppression', nou sne sommes donc pas redirigé vers l'affichage des produits
    // On redefinie donc la valeur de l'indice 'action' dans l'URL par 'affichage' pour entrer dans la condition qui execute l'affichage des produits (condition ci-dessous dans le code)
    $_GET['action'] = 'affichage';

    $vd = "<div class='col-md-5 mx-auto alert alert-success text-center>Le produit <strong>ID$_GET[id_produit]</strong> a ete supprime avec succes !</div>";

}

if($_POST)
{
    // TRAITEMENT DE LA PHOTO UPLOADE
    if(!empty($_FILES['photo']['name']))
    {
        $info = new SplFileInfo($_FILES['photo']['name']);
        //echo '<pre>'; var_dump($_info); echo '</pre>';

        //echo '<pre>'; print_r(get_class_methods($info)); echo '</pre>';

        // On stock l'extension du fichier uploade grace a la mathode getExtension() de la classe predefinie SplFileInfo
        $extFichier = $info->getExtension();
        print_r($extFichier);

        $arrayExt = ['jpg', 'png', 'jpeg'];
        //echo '<pre>'; print_r($arrayExt); echo'</pre>';
        //                              ARRAY contient toutes les extensions autorisees        
        $positionExt = array_search($extFichier, $arrayExt);
        //echo '<pre>'; var_dump($positionExt); echo'</pre>';

        // Si $positionExt retourne un boolean FALSE, cela veut dire que l'extension uploade n'est pas presente dans le tableau ARRAY des extensions autorisees
        if($positionExt === false)
        {
            $errorFile = "<small class='font-italic text-danger'>Extension non autorisee (jpg, jpeg, png).</small>";

            // Cette variable est declaree seulement dans le cas d'une mauvaise extensioin de fichier
            $error = true;
        }
        else
        {
            // On renomme la photo en concatenant la reference saisie dans le formulaire avec le nom de l'image recuperee dans la superglobale $_FILES
            $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name'];
            echo $nomPhoto . '<hr>';
            // permet de definir l'URL de la photo qui est conserve en BDD 
            $photoBdd = URL . "photo/$nomPhoto";
            echo $photoBdd . '<hr>';
            // On definit le chemin physique de l'image sur le serveur
            // ex:
            // /Applications/MAMP/htdocs/dossier.php/tp1.php/09-boutique/photo/-crimescene.jpg
            $photoDossier = RACINE_SITE . "photo/$nomPhoto";
            echo $photoDossier . '<hr>';
            // copy() : fonction predefinie permettant de copier un fichier sur le serveur
            // arguments :
            // 1. le nom temporaire de l'image accessible via la superglobale $_FILES
            //2. le chemin physique de la photo dans le dossier photo sur le serveur 
            copy($_FILES['photo']['tmp_name'], $photoDossier);
        }
    }

    // Si la variable $error n'est pas definit, cela veut dire que l'internaute n'a pas fait d'erreur sur l'extension du fichier uploade, nous pouvons executer la requete d'insertion en BDD
    if(!isset($error))
    {
        // Exo : realiser le traitement PHP/SQL permettant d'inserer un produit en BDD a la validation du formulaire (prepare + marqueur + execute)

        $data = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, photo, public, prix, stock) VALUES (:reference,:categorie, :titre, :description, :couleur, :taille, :photo, :public, :prix, :stock)");

        $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
        $data->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
        $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $data->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $data->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
        $data->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
        $data->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
        $data->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
        $data->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
        $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_STR);

        $data->execute();

    }
}
/*
    Exo : afficher l'ensemble de la table produit sous forme de tableau HTML (boucles + fetch() + table + tr + td) + 2 liens sur chaque lignes (2 boutons) pour la modification et suppression
    
    Et afficher le nombre de produits(s) enregistres dans la boutique
*/



require_once('../include/header.inc.php');
require_once('../include/nav.inc.php');
?>
<h1 class="display-4 text-center my5">BACKOFFICE</h1>

<!-- LIENS PRODUITS -->


<div class="col-md-3 mx-auto d-flex flex-column">
    <a href="?action=affichage" class="btn btn-success mb-2">AFFICHAGE DES PRODUITS</a>
    <a href="?action=ajout" class="btn btn-info">AJOUT D'UN PRODUIT</a>
</div>

<?php
if(isset($_GET['action']) && $_GET['action'] == 'affichage')
{}
echo '<h1 class="display-4 text-center my-5">Affichage des produits</h1>';
$result = $bdd->query("SELECT * FROM produit");

   //Affichage message utilisateur
   if(isset($vd)) echo $vd;

if($result->rowCount() < 2)
$txt = 'produit enregistre';
else
$txt = 'produits enregistres';

echo "<h6><span class='badge badge-success '>'" . $result->rowCount() . "</span> $txt </h6>";

echo '<table class="table table-bordered text-center  "><tr>';
for($i = 0; $i < $result->columnCount(); $i++)
{
    $colonne = $result->getColumnMeta($i);
    echo '<pre>'; print_r($colonne); echo '</pre>';

    echo "<th>" . strtoupper($colonne['name']) . "</th>";
}
echo "<th>EDIT</th>";
echo "<th>SUPP</th>";
echo '</tr>';
while($products = $result->fetch(PDO::FETCH_ASSOC))
{
    echo '<pre>'; print_r($products); echo '</pre>';

    echo '<tr>';
    foreach($products as $key => $value)
    {
        if($key == 'photo')
            echo "<td class='m-0 p-0'><img src= '$value' alt='$products[titre]' style='width: 200px;'></td>";

        else
        echo "<td class='align-middle'>$value</td>";
    }
    // On transmet l'id_produit dans l'URL dans le cas d'une modification ou suppression, cela nous permettra soit de recuperer les produit en cas
    echo "<td class='align-middle'><a href='?action=modification&id_produit=$products[id_produit]' class='btn btn-primary'><i class='far fa-edit'></i></a></td>";
    echo "<td class='align-middle'><a href='?action=suppression&id_produit=$products[id_produit]' class='btn btn-danger'><i class='far fa-trash-alt'></i></a></td>";
    echo '</tr>';
    
}
echo '</table>';

?>
 


<!-- enctype="multipart/form-data" si le formulaire contient un upload de fichier, il ne faut surtout pas oublier l'attribut 'enctype' et la valeur 'multipart/form-data 'multipart/form-data', cela permet de recuperer les informations lie au fichier uploade (nom, extension, nom tmp etc..) directement stocke dans le superglobale $_FILES -->
<form method="post" class="col-md-7 mx-auto" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="civilite">Reference</label>
            <input id="reference" name="reference" class="form-control">
        </div>
        
        <div class="form-group col-md-6">
            <label for="civilite">Categorie</label>
            <input id="categorie" name="categorie" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Titre</label>
            <input id="titre" name="titre" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Description</label>
            <input id="description" name="description" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Couleur</label>
            <input id="couleur" name="couleur" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Taille</label>
            <select id="taille" name="taille" class="form-control">
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="civilite">Public</label>
            <input id="public" name="public" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Photo</label>
            <input type="file" id="photo" name="photo" class="form-control">
            <?php if(isset($errorFile)) echo $errorFile; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Prix</label>
            <input id="prix" name="prix" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="civilite">Stock</label>
            <input id="stock" name="stock" class="form-control">
        </div>
        
    
    </div>
    <button type="submit" class="btn btn-success">AJOUT PRODUIT</button>
    </form>


<?php
require_once('../include/footer.inc.php');