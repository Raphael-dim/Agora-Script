<form method="post" action="index.php?controller=utilisateur&action=connecter">
    <fieldset>
        <legend>formulaire de connexion :</legend>
        <p>
            Identifiant de connexion :
            <input type="text" placeholder="" name="identifiant" id="id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </p>
        <p>
            <input type="submit" value="Connexion">
        </p>
        <p>
            <a href="index.php?controller=utilisateur&action=create">Pas encore de compte ?</a>

        </p>
    </fieldset>

</form>