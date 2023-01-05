<?php

use App\Vote\Lib\ConnexionUtilisateur;

?>
<form class="custom-form" method="post" action="index.php?controller=utilisateur&action=updated">
    <fieldset>
        <h2>Mise à jour de votre compte :</h2>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="username">Identifiant : </label>
            <input class="InputAddOn-field" type="text" value="<?= $utilisateur->getIdentifiant() ?>" name="identifiant"
                   id="username"
                   readonly required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="lastname">Nom&#42; : </label>
            <input class="InputAddOn-field" type="text" value="<?= $utilisateur->getNom() ?>" name="nom" id="lastname"
                   required>
            <span class="validity"></span>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="firstname">Prénom&#42; : </label>
            <input class="InputAddOn-field" type="text" value="<?= $utilisateur->getPrenom() ?>" name="prenom"
                   id="firstname" required>
            <span class="validity"></span>

        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mail">Email : </label>
            <input class="InputAddOn-field" type="text" value="<?= $utilisateur->getMail() ?>" name="mail"
                   id="mail" readonly required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Ancien mot de passe&#42; : </label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="ancienMDP" id="mdp_id"
                   required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42; : </label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>

        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#42; : </label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
        </p>

        <?php
        if (ConnexionUtilisateur::estAdministrateur()) {
            echo '<p class="InputAddOn">
                    <label class="InputAddOn-item" for="estAdmin_id">Administrateur&#42; : </label>
                    <input class="InputAddOn-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id"';
            if ($utilisateur->isEstAdmin()) {
                echo ' checked ';
            }
            echo '></p>';
        }
        ?>

        <p>
            <input type="submit" value="Mettre à jour" class="nav">
        </p>
    </fieldset>
</form>