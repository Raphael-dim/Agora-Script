<form method="post" action="index.php?controller=utilisateur&action=connected">
    <fieldset>
        <legend>formulaire de connexion :</legend>
        <p>
            Identifiant de connexion :
            <input type="text" placeholder="" name="identifiant" id="id" required>
        </p>
        <p>
            Mot de passe :
            <input type="text" placeholder="" name="mdp" id="password" required>
        </p>

        <p>
            <input type="submit" value="Connexion">
        </p>
        <p>
            <a href="index.php?controller=utilisateur&action=create">Pas encore de compte ?</a>

        </p>
    </fieldset>

</form>