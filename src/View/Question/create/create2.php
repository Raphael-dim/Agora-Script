<?php
if (isset($_POST["nbSections"])){
    $nbSection = $_POST["nbSections"];
}

else{
    $nbSection = 1;
}
if (isset($_POST["titre"])){
    $titre = htmlspecialchars($_POST["titre"]);
}
else {
    $titre = "";
}


?>
<h1><?php echo $titre ?></h1>


<form method=\"get\" action='../web/frontController.php'>
    <input type='hidden' name='action' value='created'>
    <fieldset>

        <?php

        if (!isset($nbSection)){
            $nb = 0;
        }
        else{
            $nb = $nbSection;
        }
        for ($i = 1; $i <= $nb; $i++) {
            echo "<p>
            <label for='nbSections_id'>Titre de la section n°" . $i . "</label> :
            <input type='text' name=titre".$i." id='' required/></p>
            <p>
            <label for='nbSections_id'>Description de la section" . $i . "</plabel> :
            <input type='text' name=description".$i." id='sections_id' required/>
            </p>";
        }

        ?>
    </fieldset>
    <input type="submit" value="Créer"/>
</form>
<form method = "post" action = "index.php?controller=question&action=create">
      <input type = hidden value = <?php echo $_POST["titre"] ?> name = titre />
      <input type = hidden value = <?php echo $_POST["nbSections"] ?> name = nbSections />
    <input type="submit" value="Retour"/>
</form>
