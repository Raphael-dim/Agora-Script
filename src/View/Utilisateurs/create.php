<form method="post" action="index.php?controller=utilisateur&action=created">
    <fieldset>
        <legend>Formulaire d'inscription :</legend>
        <p>
            Nom d'utilisateur :
            <input type="text" placeholder="" name="identifiant" id="username" required>
        </p>
        <p>
            Nom :
            <input type="text" placeholder="" name="nom" id="lastname" required>
        </p>
        <p>
            Prenom :
            <input type="text" placeholder="" name="prenom" id="firstname" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
        </p>
        <p>
            <input type="submit" value="Inscription">
        </p>
    </fieldset>
</form>