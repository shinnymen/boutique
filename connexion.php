<?php
require_once('include/init.inc.php');

// controle du formulaire 
//echo '<pre>'; print_r($_POST); echo '</pre>';

// DECONNEXION
// si l'indice 'action' est definit dans l'URL et qu'il a pour valeur 'deconnexion', cela veut dire que l'internaute a clique sur le lien 'deconnexion' et par consequent transmit dans l'url 'action=deconnexion', alors on entre dans le IF et on supprime le tableau ARRAY a l'indice 'user', c'est ce qui permettait de l'identifier sur le site.
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    unset($_SESSION['user']);
}
// Si l'internaute est identifie sur le site, c'est a dire que l'indice 'user' est definit dans la session, alors l'internaute n;a rien a faire sur la page de connexion.php, on le redirige automatiquement vers sa page profil
if(connect())
{
    header('location: profil.php');
}



if($_POST)
{
    $data = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $data->bindValue(':pseudo', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data->bindValue(':email', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data->execute();

    // SI la requete de selection retourne 1 resultat, cela veut dire que l'internaute a saisi le bon email/ pseudo, donc la requete de selection retourne 1 ligne de la table SQL 'membre' de la BDD
    if($data->rowCount() > 0)
    {
        //echo 'pseudo / email OK!';

        // On execute fetch() afin d'obtenir un ARRAY contenant les donnees en BDD de l'internaute qui a saisi le bon pseudo / email
        $user = $data->fetch(PDO::FETCH_ASSOC);
        //echo '<pre>'; print_r($user); echo '</pre>';

        // SI le mot de passe de la BDD est egal au mot de passe que l'internaute a saisie dans le formulaire, on entre dans le IF
        //if($user['mdp'] == $_POST['mdp'])
        
        if(password_verify($_POST['mdp'], $user['mdp']))
        {
            //echo 'mot de passe OK !';

            foreach($user as $key => $value)
            {
                if($key != 'mdp')
                {
                    $_SESSION['user'][$key] = $value;
                }
            }
            // Une fois les donnees enregistrees en session, on redirige le user vers sa page profil
            header('location: profil.php');
            //echo '<pre>'; print_r($_SESSION); echo '</pre>';
        }
        else
        {
            echo 'erreur mot de passe !';
        }
    }  
    else // Sinon, la requete de selection ne retourne aucun resultat de la BDD, l'internaute n'a pas saisi un pseudo / email existant en BDD
    {
        //echo 'pseudo / email ERREUR !';
        $error = "<div class='col-md-2 alert alert-danger mx-auto text-center'>Identifiants errones.</div>";
    }
}

require_once('include/header.inc.php');
require_once('include/nav.inc.php');

?>
<h1 class="display-4 text-center py-5">Identifiez-vous</h1>

<?php 
// affichage message error utilisateur
if(isset($error)) echo $error;
 ?>

<form method="post" class="col-md-3 mx-auto">
  <div class="form-group">
    <label for="pseudo_email">Email/pseudo</label>
    <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" value=" <?php if(isset ($_POST['pseudo_email'])) echo $_POST['pseudo_email'];?>">
   
  </div>
  <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="form-control" id="mdp" name="mdp">
  </div>    
  </div>
  <button type="submit" class="btn btn-secondary">Submit</button>
</form>

<?phprequire_once('include/footer.inc.php');