<form method="post" action="index.php?controller=utilisateur&action=connecter">
    <fieldset>
        <h2 style="color: #02243a ">Formulaire de connexion :</h2>
        <p>
            <label for="id">Identifiant de connexion :</label>
            <input type="text" placeholder="" name="identifiant" id="id" required>
            <span class="validity"></span>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
            <span class="validity"></span>
        </p>
            <input type="submit" value="Connexion" id="suivant" class="nav">
            <a id="precedent" style="color: black" href="index.php?controller=utilisateur&action=create">Pas encore de compte ?</a>
    </fieldset>

</form>