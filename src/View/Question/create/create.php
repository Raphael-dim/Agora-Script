<?php

if ((isset($_POST["titre"]) && isset($_POST["nbSections"]))) {
    $titre = htmlspecialchars($_POST["titre"]);
    $nbSection = htmlspecialchars($_POST["nbSections"]);
} else {
    $titre = "";
    $nbSection = "";
}

?>
<form method="post" action="index.php?controller=question&action=create2">
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p>
            <label for="titre_id">Titre</label> :
            <input type="text" placeholder="Ex : " value="<?php echo $titre; ?>" name="titre" id="titre_id" required/>
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


