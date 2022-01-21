<?php
require_once('include/init.inc.php');

// Exo : 

// 2. Contrôler en PHP que l'on receptionne bien toute les données saisie dans le formulaire (print_r)
echo '<pre>'; print_r($_POST); echo '</pre>';

if(connect())
{
    header('location: profil.php');
}

if($_POST)
{
    // classe affecté au champ input avec une bordure rouge en cas d'erreur utilisateur
    $border = 'border border-danger';

    // 3. Contrôler la disponibilité du pseudo (unique en BDD)

    // On selectionne en BDD toute les données par raaport au pseudo que l'internaute a saisie dans le champ 'pseudo' du formulaire
    //                                                                 toto78
    $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();

    // echo "nombre de pseudo existant : " . $verifPseudo->rowCount() . '<hr>'; 

    // 6. Faites en sorte d'informer l'internaute si la champ pseudo est laissé vide
    if(empty($_POST['pseudo']))
    {
        $errorPseudo = '<small class="font-italic text-danger">Merci de renseigner un pseudo.</small>';

        $error = true;
    }
    elseif($verifPseudo->rowCount() > 0)
    {
        // Si la requete de selection retourne un résultat supérieur à 0, cela veut que le pseudo saisie par l'internaute est existant en BDD
        $errorPseudo = '<small class="font-italic text-danger">Ce pseudo est déjà existant. Merci d\'en saisir un nouveau.</small>';

        $error = true;
    }

    // 4. Contrôler la disponibilité de l'email (unique en BDD)
    //                                                          gregory@evogue.fr
    $verifEmail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $verifEmail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $verifEmail->execute();

    // 5. Faites en sorte d'informer l'internaute si le champ email n'est pas du bon format et si le champ est laissé vide
    if(empty($_POST['email']))
    {
        $errorEmail = '<small class="font-italic text-danger">Merci de saisir une adresse Email.</small>';

        $error = true;
    }
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $errorEmail = '<small class="font-italic text-danger">Adresse Email non valide (ex: exemple@gmail.com).</small>';

        $error = true;
    }
    elseif($verifEmail->rowCount() > 0)
    {
        $errorEmail = '<small class="font-italic text-danger">Compte exsistant. Merci de vous connecter ou de saisir une nouvelle adresse.</small>';

        $error = true;
    }

    // 7. Faites en sorte d'informer l'internaute si les mots de passe ne correspondent pas
    if($_POST['mdp'] != $_POST['confirm_mdp'])
    {
        $errorMdp = '<small class="font-italic text-danger">Vérifier les mots de passe.</small>';

        $error = true;
    }

    // Expression régulière (REGEX)

    /*
        preg_match() : fonction prédéfinie permettant de définir une expression régulière
        une expression régulière (REGEX) est toujours entouré de dieze # afin de préciser les options : 
            ^ indique le début de la chaine 
            $ permet d'indiquer la fin de la chaine 
            + est la pour indiquer que les caractères peuvent être utilisés plusieurs fois
            {2,20} permet d'indiquer la taille de la chaine
            [a-zA-Zéèàê-] indique les caractères autorisés dans la chaine de caractères
    */
    if(!preg_match('#^[a-zA-Zéèàê-]{2,20}+$#', $_POST['prenom']))
    {
        $errorPrenom = '<small class="font-italic text-danger">Votre prénom contient des caractères non autorisés.</small>';

        $error = true;
    }

    if(!isset($error))
    {
        // Hachage du mot de passe 
        // Les mots de passe en BDD ne sont jamais conserve en cair, nous devons creer une cle de hachage
        // password_hash() : fonction predefinie permettant de creer une cle de hachage a partir d'un algorythme (PASSWORD_RCRYPT)
        // A la connexion, pour comparer la cle de hachage, nous executerons la fonction password_verify()
        // Exo : réaliser le traitement PHP/SQL permettant d'insérer un utilisateur dans la BDD (PREPARE + INSERT + :marqeur + BINDVALUE + EXECUTE)

        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
        $insertUser = $bdd->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)"); 

        $insertUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $insertUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
        $insertUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $insertUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $insertUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $insertUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $insertUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $insertUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $insertUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);

        $insertUser->execute();
        // On redirige apres la validation de l'inscription vers le fichier validation_inscription.php
        header('location: validation_inscription.php');
    }
}

require_once('include/header.inc.php');
require_once('include/nav.inc.php');
?>

<h1 class="display-4 text-center py-5">Créer votre compte</h1>

<!-- 1. Créer un formulaire HTML correspondant à la table 'membre' de la BDD + champ 'confirmer mot de passe' (name="confirm_mdp") (sauf les champs id_membre et statut) -->
<form method="post" class="col-md-7 mx-auto">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="civilite">Civilité</label>
            <select id="civilite" name="civilite" class="form-control">
                <option value="femme">Madame</option>
                <option value="homme">Monsieur</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="pseudo">Pseudo</label>
            <!-- Si le champ 'pseudo' n'est pas rempli par l'internaute, alors la variable $errorPseudo est définit, on affecte la classe 'border border-danger' au champ input via la variable $border -->
            <input type="text" class="form-control <?php if(isset($errorPseudo)) echo $border; ?>" id="pseudo" name="pseudo" placeholder="Saisir votre pseudo...">
            <?php if(isset($errorPseudo)) echo $errorPseudo; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="mdp">Mot de passe</label>
            <input type="text" class="form-control <?php if(isset($errorMdp)) echo $border; ?>" id="mdp" name="mdp" placeholder="Saisir votre mot de passe...">
        </div>
        <div class="form-group col-md-6">
            <label for="confirm_mdp">Confirmer votre mot de passe</label>
            <input type="text" class="form-control <?php if(isset($errorMdp)) echo $border; ?>" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmer votre mot de passe...">
            <?php if(isset($errorMdp)) echo $errorMdp; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom...">
        </div>
        <div class="form-group col-md-6">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control <?php if(isset($errorPrenom)) echo $border; ?>" id="prenom" name="prenom" placeholder="Saisir votre prenom...">
            <?php if(isset($errorPrenom)) echo $errorPrenom; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control <?php if(isset($errorEmail)) echo $border; ?>" id="email" name="email" placeholder="Saisir votre email...">
        <?php if(isset($errorEmail)) echo $errorEmail; ?>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="adresse">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Saisir votre adresse...">
        </div>
        <div class="form-group col-md-4">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" placeholder="Saisir votre ville...">
        </div>
        <div class="form-group col-md-2">
            <label for="code_postal">Code Postal</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Saisir votre code postal...">
        </div>
    </div>
    <button type="submit" class="btn btn-success">Valider votre compte</button>
    </form>


<?php
require_once('include/footer.inc.php');