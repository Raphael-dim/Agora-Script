<?php

if ((isset($_POST["keyword"]) && isset($_POST["row"]))) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $row = htmlspecialchars($_POST["row"]);
} else {
    $keyword = "";
    $row = "";
}
?>

<form method="post" >
    <fieldset>
        <legend>Selectionner des <?php echo $_SESSION['type'] ?> :</legend>
        <p>
            <input type="text" placeholder="" name="keyword" id="motclef" value ="<?php echo $keyword ?>"required/>
            <input type ="hidden" name = "row" value = "nom" />
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>