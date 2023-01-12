<link href="css/ListeQuestion.css" rel="stylesheet">
<form class="custom-form" method="post" action="index.php?controller=message&action=readKeyword">
    <fieldset>
        <h2 style="color: #022234">Envoyer un message :</h2>
        <p>
            <label for="motclef"></label><input placeholder="Rechercher un utilisateur" type="text"
                                                name="keyword" id="motclef"
                                                required>
            <input style="max-height: 35px" type="image" alt="Submit" src="../web/images/search.png" class="search">
        </p>
        <?php
        if (isset($utilisateurs)) {
            foreach ($utilisateurs as $utilisateur) {
                echo ' <div class="contact">
             <img style="max-height: 40px" src="images/profil.png" alt="profil">
            <a>' . htmlspecialchars($contact->getPrenom()) . ' ' . htmlspecialchars($contact->getNom()) . '</a>

                <a  style="min-width: 100%" href="index.php?action=read&controller=message&idContact=' . rawurlencode($utilisateur->getIdentifiant()) . '">
                ' . htmlspecialchars($utilisateur->getIdentifiant()) . '</a></div>';
            }
        }
        ?>
        <input id="suivant" type="submit" value="Suivant" class="nav">

    </fieldset>
</form>