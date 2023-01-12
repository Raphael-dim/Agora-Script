<link href="css/ListeQuestion.css" rel="stylesheet">
<form class="custom-form" method="post" action="index.php?controller=message&action=readKeyword">
    <fieldset>
        <h2 style="color: #022234">Envoyer un message :</h2>
        <p>
            <label for="motclef"></label><input style="max-width: 200px" placeholder="Rechercher un utilisateur"
                                                type="text"
                                                name="keyword" id="motclef"
                                                required>
            <input style="max-height: 35px" type="image" alt="Submit" src="../web/images/search.png" class="search">
        </p>
        <?php
        if (isset($utilisateurs)) {
            foreach ($utilisateurs as $utilisateur) {
                echo '<div class="contact">
            <img style="max-height: 40px" src="images/profil.png" alt="profil">
            <a href="index.php?action=read&controller=utilisateur&idUtilisateur=' . $utilisateur->getIdentifiant() . '">' . htmlspecialchars($utilisateur->getPrenom()) . ' ' . htmlspecialchars($utilisateur->getNom()) . '</a>
            <a style="min-width: 100%" href="index.php?controller=message&action=read&idContact=' . rawurlencode($utilisateur->getIdentifiant()) . '#dernierMessage">Envoyer un message</a></div>';
            }
        }
        ?>
        <input id="suivant" type="submit" value="Suivant" class="nav">

    </fieldset>
</form>