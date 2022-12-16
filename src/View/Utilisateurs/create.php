<form class="custom-form" method="post" action="index.php?controller=utilisateur&action=created" class="inscription">
    <fieldset>
        <h2 style="color: #022234">Formulaire d'inscription :</h2>
        <p>
            <label for="username">Identifiant : </label><input type="text" placeholder="" name="identifiant"
                                                               id="username" required>
            <span class="validity"></span>

        </p>
        <p>
            <label for="lastname">Nom : </label><input type="text" placeholder="" name="nom" id="lastname" required>
            <span class="validity"></span>

        </p>
        <p>
            <label for="firstname">Prénom : </label><input type="text" placeholder="" name="prenom" id="firstname"
                                                           required>
            <span class="validity"></span>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42; : </label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
<!--            <span class="validity"></span>-->

        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#42; : </label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
<!--            <span class="validity"></span>-->

        </p>
        <input id="suivant" type="submit" value="Inscription" class="nav">

    </fieldset>
</form>