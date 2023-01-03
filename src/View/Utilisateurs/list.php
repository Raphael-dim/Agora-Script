<?php

require "../src/View/Utilisateurs/search.php";
echo '<hr style="margin-bottom: 20px">';
foreach ($utilisateurs as $utilisateur) {
    $nom = htmlspecialchars($utilisateur->getNom());
    $prenom = htmlspecialchars($utilisateur->getPrenom());
    $urlidentifiant = rawurlencode($utilisateur->getIdentifiant());
    echo '<div class="user">
          <a href = "index.php?action=read&idUtilisateur='
        . $urlidentifiant . '&controller=utilisateur">' . $nom . ' ' . $prenom . '</a>';
    echo '<div class="action">
           <a href ="index.php?action=update&controller=utilisateur&idUtilisateur=' .
        $urlidentifiant . '">
           <img class="modifier" src = "../web/images/modifier.png"  alt="modifier"></a>
           <a href ="index.php?action=delete&controller=utilisateur&idUtilisateur=' .
        $urlidentifiant . '">   
           
            <img class="delete" src = "../web/images/delete.png"  alt="supprimer"></a>
            </div>
            </div>
            ';
}