<form method="post" action="index.php?controller=utilisateur&action=readAll">
    <fieldset>
        <legend>Formulaire d'inscription : </legend>
        <p>
            Nom :
            <input type="text" placeholder="" name="nom" id="lastname" required>
        </p>
        <p>
            Prenom :
            <input type="text" placeholder="" name="prenom" id="firstname" required>
        </p>
        <p>
            Email :
            <input type="text" placeholder="" name="email" id="mail" required>
        </p>
        <p>
            Nom d'utilisateur :
            <input type="text" placeholder="" name="identifiant" id="username" required>
        </p>
        <p>
            Mot de passe :
            <input type="password" placeholder="" name="mdp" id="password" required>
        </p>
        <p>
            <input type="submit" value="Inscription">
        </p>

    </fieldset>
</form>