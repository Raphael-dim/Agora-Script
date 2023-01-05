<form class="custom-form" method="post" action="index.php?controller=utilisateur&action=created">
    <fieldset>
        <h2 style="color: #022234">Formulaire d'inscription :</h2>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="username">Identifiant&#42; : </label>
            <input class="InputAddOn-field" type="text" placeholder="" name="identifiant" id="username" required>
            <span class="validity"></span>

        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="lastname">Nom&#42; : </label>
            <input class="InputAddOn-field" type="text" placeholder="" name="nom" id="lastname" required>
            <span class="validity"></span>

        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="firstname">Prénom&#42; : </label>
            <input class="InputAddOn-field" type="text" placeholder="" name="prenom" id="firstname" required>
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
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="email_id">Email&#42; : </label>
            <input class="InputAddOn-field" type="email" value="" placeholder="toto@yopmail.com" name="email"
                   id="email_id" required>
        </p>
        <?php

        use App\Vote\Lib\ConnexionUtilisateur;

        if (ConnexionUtilisateur::estAdministrateur()) {
            echo '<p class="InputAddOn">
                    <label class="InputAddOn-item" for="estAdmin_id">Administrateur&#42; : </label>
                    <input class="InputAddOn-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">
                </p>';
        }
        ?>
        <input id="suivant" type="submit" value="Inscription" class="nav">

    </fieldset>
</form>