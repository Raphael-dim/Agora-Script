<?php
foreach ($utilisateurs as $utilisateur) {
    $identifiant = rawurlencode($utilisateur->getIdentifiant());
    $urlidentifiant = rawurlencode($utilisateur->getIdentifiant());
    echo '<p> <a href = "../web/frontController.php?action=read&login='
        . $urlidentifiant. '&controller=utilisateur">' .$identifiant . '</a> </p> ';
}

?>