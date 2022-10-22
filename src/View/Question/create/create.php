<?php
session_start();
$_SESSION['auteurs'] = array();
$_SESSION['votants'] = array();
$_SESSION['post'] = array();
$_SESSION['sections'] = array();
?>
<form method="post" action="index.php?controller=question&action=create2">
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p>
            <label for="titre_id">Titre</label> :
            <input type="text" placeholder="Ex : " name="Titre" id="titre_id" required/>
        </p>
        <p>
            <label for="nbSections_select">Nombre de sections</label>
            <select name="nbSections" id="nbSections_select">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                        echo "<option value=" . $i . ">" . $i . "</option>";
                } ?>
            </select>
        </p>
    </fieldset>
    <input type="submit" value="Mettre à jour"/>


