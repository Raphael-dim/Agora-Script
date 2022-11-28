<?php
echo '<h1>' . $Utilisateur->getNom() . '</h1>';
?>

<ul>
    <li>
        <p><a href="">Modifier le mot de passe</a></p>

    </li>
    <li>
        <p><a href="">Modifier le nom d'utilisateur</a></p>
    </li>
    <li>
       <p><a href='index.php?action=disconnected&controller=utilisateur'>Déconnexion</a></p>
    </li>
</ul>
