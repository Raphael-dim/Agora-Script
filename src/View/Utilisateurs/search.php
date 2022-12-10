<?php


?>

<form method="post" action="index.php?controller=utilisateur&action=readKeyword">
    <fieldset>
        <legend>Chercher un utilisateur :</legend>
        <p>
            <input type="text" placeholder="" name="keyword" id="motclef" required>
            <input type ="hidden" name = "row" value = "nom" >
        </p>
        <p>
            <input type="submit" value="Envoyer" >
        </p>
    </fieldset>
</form>