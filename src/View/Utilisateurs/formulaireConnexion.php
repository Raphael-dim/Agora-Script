<form class="custom-form" method="post" action="index.php?controller=utilisateur&action=connecter">
    <fieldset>
        <h2 style="color: #02243a ">Formulaire de connexion :</h2>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="id">Identifiant de connexion&#42; :</label>
            <input class="InputAddOn-field" maxlength="30" type="text" placeholder="" name="identifiant" id="id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" maxlength="256" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </p>
        <input type="submit" value="Connexion" id="suivant" class="nav">
        <a class="link-custom" id="precedent" style="text-decoration: none; color: #000e17" href="index.php?controller=utilisateur&action=create">Pas encore de
            compte ?</a>
    </fieldset>

</form>