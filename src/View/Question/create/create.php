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
        <p>
            <label for="debutEcriture">Date de début d'écriture des propositions :</label>
            <input type="date" id="debutEcriture" name="debutEcriture"
                   value="<?= date('Y-m-d'); ?>"
                   min="<?= date('Y-m-d'); ?>"/>
        </p>
        <p>
            <label for="finEcriture">Date de fin d'écriture des propositions :</label>
            <input type="date" id="finEcriture" name="finEcriture"
                   value="<?= date('Y-m-d'); ?>"
                   min="<?= date('Y-m-d'); ?>"/>
        </p>
        <p>
            <label for="debutVote">Date de début des votes :</label>
            <input type="date" id="debutVote" name="debutVote"
                   value="<?= date('Y-m-d'); ?>"
                   min="<?= date('Y-m-d'); ?>"/>
        </p>
        <p>
            <label for="finVote">Date de fin des votes :</label>
            <input type="date" id="finVote" name="finVote"
                   value="<?= date('Y-m-d'); ?>"
                   min="<?= date('Y-m-d'); ?>"/>
        </p>

    </fieldset>
    <input type="submit" value="Mettre à jour"/>
</form>
<?php
if (isset($message)) {
    echo "<div class=\"message\"><p><img src=\"/web/images/attention.png\" class=\"attention\"  alt=\"Warning\"> " . $message . "</p></div>";
} ?>


