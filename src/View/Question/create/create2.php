<?php
session_start();
$nbSection = $_POST["nbSections"];

foreach ($_POST as $key => $value) {
    $_SESSION['post'][$key] = $value;
}
var_dump($_SESSION['post']);
?>
<h1><?php echo $_POST["Titre"] ?></h1>


<form method= post action='index.php?controller=question&action=search'>
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
