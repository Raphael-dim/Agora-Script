<?php
foreach ($utilisateurs as $utilisateur) {
    $nom =   rawurlencode($utilisateur->getNom());
    $prenom =   rawurlencode($utilisateur->getPrenom());
    $urlidentifiant = rawurlencode($utilisateur->getIdentifiant());
    echo '<p> <a href = "../web/frontController.php?action=read&login='
        . $urlidentifiant. '&controller=utilisateur">' .$nom.' '.$prenom . '</a> </p> ';
}

?>