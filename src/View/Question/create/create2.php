<?php
if (isset($_POST["nbSection"])){
    $nbSection = $_POST["nbSection"];
}
else{

}

?>


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
            <label for='nbSections_id'>Titre de la section n°" . $i . "</plabel> :
            <input type='text' name=titre".$i." id='' required/>
            <label for='nbSections_id'>Corp du texte" . $i . "</plabel> :
            <input type='text' name=corp".$i." id='nbSections_id' required/>
            </p>";
        }

        ?>
    </fieldset>
    <input type="submit" value="Créer"/>
</form>
<form method = "post" action = "index.php?controller=question&action=create")>
      <input type = hidden value = <?php echo $_POST["Titre"] ?> name = Titre />
      <input type = hidden value = <?php echo $_POST["nbSection"] ?> name = nbSection />
    <input type="submit" value="Retour"/>
</form>
