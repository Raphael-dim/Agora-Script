<form class="custom-form" method="post" action="index.php?controller=message&action=readKeyword" class="inscription">
    <fieldset>
        <h2 style="color: #022234">Envoyer un message :</h2>
        <p>
            <label for="motclef"></label><input placeholder="Rechercher une question" type="text" placeholder=""
                                                name="keyword" id="motclef"
                                                required>
            <input type="image" alt="Submit" src="../web/images/search.png" class="search">
        </p>
        <?php
        if (isset($utilisateurs)) {
            foreach ($utilisateurs as $utilisateur) {
                echo '<br><a href="index.php?action=read&controller=message&idContact=' . $utilisateur->getIdentifiant() . '">
                ' . $utilisateur->getIdentifiant() . '</a>';
            }
        }
        ?>
        <input id="suivant" type="submit" value="Suivant" class="nav">

    </fieldset>
</form>