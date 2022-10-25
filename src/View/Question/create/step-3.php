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
        echo '<p>
            <label>Titre de la section n°' . $i . '</label> :
            <input type="text" name=titre' . $i . ' value = "' . FormConfig::TextField('titre' . $i) . '" id="titre_id"  required/></p>
            <p>
            <label>Description de la section n°' . $i . '</label> :
            <input type="text" name=description' . $i . ' value = "' . FormConfig::TextField('description' . $i) . '" id="sections_id" required/>
            </p>';
    }

    ?>

    <input type="submit" name=previous value="Retour" class="nav" formnovalidate/>
    <input type="submit" name=next value="Suivant" class="nav"/>
</form>
