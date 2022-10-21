<?php

if ((isset($_POST["Titre"]) && isset($_POST["nbSections"]))) {
    $Titre = htmlspecialchars($_POST["Titre"]);
    $nbSection = htmlspecialchars($_POST["nbSections"]);
} else {
    $Titre = "";
    $nbSection = "";
}

?>
<form method="post" action="index.php?controller=question&action=create2">
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p>
            <label for="titre_id">Titre</label> :
            <input type="text" placeholder="Ex : " value="<?php echo $Titre; ?>" name="Titre" id="titre_id" required/>
        </p>
        <p>
            <label for="nbSections_select">Nombre de sections</label>
            <select name="nbSections" id="nbSections_select">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    if ($nbSection == $i) {
                        echo "<option value=" . $i . " selected '>" . $i . "</option>";
                    }
                    else{
                        echo "<option value=" . $i . ">" . $i . "</option>";
                    }
                } ?>
            </select>
        </p>
    </fieldset>
    <input type="submit" value="Mettre à jour"/>


