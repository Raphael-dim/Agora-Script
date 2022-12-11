<form method="post" action="index.php?controller=utilisateur&action=updated">
    <fieldset>
        <legend>Mise à jour de votre compte :</legend>
        <p>
            Nom d'utilisateur :
            <input type="text" value="<?= $utilisateur->getIdentifiant() ?>" name="identifiant" id="username"
                   readonly required>
        </p>
        <p>
            Nom :
            <input type="text" value="<?= $utilisateur->getNom() ?>" name="nom" id="lastname" required>
        </p>
        <p>
            Prenom :
            <input type="text" value="<?= $utilisateur->getPrenom() ?>" name="prenom" id="firstname" required>
        </p>
        <p>
            <label for="mdp_id">Ancien mot de passe&#42;</label>
            <input type="password" value="" placeholder="" name="ancienMDP" id="mdp_id" required>
        </p>
        <p>
            <label for="mdp2_id">Mot de passe&#42;</label>
            <input type="password" value="" placeholder="" name="mdp" id="mdp2_id" required>
        </p>
        <p>
            <label for="mdp3_id">Vérification du mot de passe&#42;</label>
            <input type="password" value="" placeholder="" name="mdp2" id="mdp3_id" required>
        </p>
        <p>
            <input type="submit" value="Mettre à jour" class="nav">
        </p>
    </fieldset>
</form>