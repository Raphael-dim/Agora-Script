<?php

require "../src/View/Utilisateurs/search.php";
foreach ($utilisateurs as $utilisateur) {
    $nom =   htmlspecialchars($utilisateur->getNom());
    $prenom =   htmlspecialchars($utilisateur->getPrenom());
    $urlidentifiant = rawurlencode($utilisateur->getIdentifiant());
    echo '<p> <a href = "../web/frontController.php?action=read&login='
        . $urlidentifiant. '&controller=utilisateur">' .$nom.' '.$prenom . '</a> </p> ';
}

?>