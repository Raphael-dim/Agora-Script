<form method="post" action="../web/index.php?action=readKeyword&controller=utilisateur">
    <fieldset>
        <legend>Chercher un utilisateur :</legend>
        <p>
            <input type="text" placeholder="" name="keyword" id="motclef" required/>
            <input type ="hidden" name = "row" value = "nom" />
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>