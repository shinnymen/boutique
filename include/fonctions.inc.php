<?php
    // FONCTION INTERNAUTE IDENTIFIE / CONNECTE SUR LE SITE

    function connect()
    {
        // Si l'indice 'user' dans la session N'EST PAS (!) definit (isset), cela veut dire que l'internaute n'est pas identifie sur le site, donc i n;est pas passe par la page connexion, alors on retourne FALSE
        if(!isset($_SESSION['user']))
        {
            return false;
        }
        else // Sinon, l'indice 'user' dans la session est bien definit, cela veut dire que l'internaute est bien identifie sur le site, alors on retourne TRUE
        {
            return true;
        }
    }

    // FONCTION INTERNAUTE IDENTIFIE / CONNECTE SUR LE SITE ET ADMINISTRATEUR


function adminConnect()
{
    // SI l'internaute est identifie sur le site (connect()) et que le statut de l'internaute dans la session est 'admin', alors l'utilisateur est administrateur du site, alors on retourne TRUE
    if(connect() && $_SESSION['user']['statut'] == 'admin')
    {
        return true;
    }
    else
    {
        return false;
    }
}