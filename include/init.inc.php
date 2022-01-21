<?php 

// CONNEXION BDD
$bdd = new PDO('mysql:host=localhost:9888;dbname=e-commerce', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// SESSION
session_start();

// CONSTANTES

define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/dossier.php/tp1.php/09-boutique/');

// echo RACINE_SITE . '<hr>'; --> C:/xampp/htdocs/PHP/09-boutique/
// echo $_SERVER['DOCUMENT_ROOT'] . '<hr>'; --> C:/xampp/htdocs

// Cette constante retourne le chemin physique du dossier 09-boutique sur le serveur local xampp
// Lors de l'enregistrement d'une photo, nous aurons besoin du chemin physique complet vers le dossier photo sur le serveur pour enregistrer la photo dans le bon dossier
// On appel $_SERVER['DOCUMENT_ROOT'] parce que chaque serveur possède des chemins différents

define("URL", "http://localhost:8888/dossier.php/tp1.php/09-boutique/");

// Cette constante servira par exemple à enregistrer l'URL d'une image en BDD. On ne conserve jamais l'image elle même en BDD
// Elle pourra permettre aussi de définir des liens absolue pour éviter des erreurs 404 en fonction du dossier ou de la page ou l'on se trouve

// Ex : 
// http://localhost/PHP/09-boutique/photo/45A78-tee-shirt.jpg

// FAILLES XSS
foreach($_POST as $key => $value)
{
    // On execute htmlspecialchars() sur chaque valeur saisie dans les formulaires
    $_POST[$key] = htmlspecialchars(trim($value));
}

// INCLUSION 
// on inclue directement les fonctions dans le fichier init.inc.php, ce qui évite de l'inclure à chaque fois sur toute les pages
require_once('fonctions.inc.php');