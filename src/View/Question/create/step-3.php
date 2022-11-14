<?php
session_start();

use App\Vote\Config\FormConfig as FormConfig;

if (!isset($_SESSION['step'][2])) {
    FormConfig::redirect("index.php?controller=question&action=create");
}

if (isset($_POST['next'])) {
    FormConfig::postSession();
    $_SESSION['step'][3] = 3;
    FormConfig::redirect("index.php?controller=question&action=form&step=4");
} else if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=2");
}

$nbSection = $_SESSION['nbSections'];
?>
<h1>Organisation des sections</h1>


<form method=post>


    <?php

    if (!isset($nbSection)) {
        $nb = 0;
    } else {
        $nb = $nbSection;
    }
    for ($i = 1; $i <= $nb; $i++) {
        echo '  
                <h2>Section n°' . $i . '</h2>
                <p>
                    <label>Titre :</label>
                    <input type="text" name=titre' . $i . ' id="titre_id"  size="77" maxlength="70"
                    value = "' . FormConfig::TextField('titre' . $i) . '" 
                    required/>
                    <label for="max_id">70 caractères maximum</label>
                </p>
                <p class ="champ">
                    <label>Description :</label>
                    <textarea id="section_id" maxlength="350" name=description' . $i . ' rows="7" cols="50" required>' . FormConfig::TextField('description' . $i) . '</textarea>
                    <label for="max_id">350 caractères maximum</label>
               </p>';
    }

    ?>

    <input type="submit" name=previous value="Retour" class="nav" formnovalidate/>
    <input type="submit" name=next value="Suivant" class="nav"/>
</form>
