<?php

use App\Vote\Config\FormConfig as FormConfig;

if (!isset($_SESSION[FormConfig::$arr]['step'][1])) {
    FormConfig::redirect("index.php?controller=question&action=create");
}

if (isset($_POST['next'])) {
    /*
     * Vérification de la continuité des dates
     */
    $debutEcriture = $_POST['debutEcriture'];
    $finEcriture = $_POST['finEcriture'];
    $debutVote = $_POST['debutVote'];
    $finVote = $_POST['finVote'];
    if ($debutEcriture > $finEcriture) {
        $message = "Date de fin d'écriture inférieure à date de début d'écriture";
    } else if ($debutVote > $finVote) {
        $message = "Date de fin de vote inférieure à date de début de vote";
    } else if ($debutVote < $debutEcriture || $debutVote < $finEcriture) {
        $message = "La phase de vote doit commencer après la phase d'écriture";
    } else {
        FormConfig::postSession();
        $_SESSION[FormConfig::$arr]['step'][2] = 2;
        FormConfig::redirect("index.php?controller=question&action=form&step=3");
    }


} else if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=1");
}

?>
<h1>Selection du calendrier</h1>

<form method="post">
    <p>
        <label for="debutEcriture">Date de début d'écriture des propositions :</label>
        <input type="datetime-local" id="debutEcriture" name="debutEcriture"
               value="<?= FormConfig::TextField('debutEcriture') ?>"
               min="<?= date("Y-m-d H:i"); ?>" required>
    </p>
    <p>
        <label for="finEcriture">Date de fin d'écriture des propositions :</label>
        <input type="datetime-local" id="finEcriture" name="finEcriture"
               value="<?= FormConfig::TextField('finEcriture') ?>"
               min="<?= date("Y-m-d H:i"); ?>" required>
    </p>
    <p>
        <label for="debutVote">Date de début des votes :</label>
        <input type="datetime-local" id="debutVote" name="debutVote"
               value="<?= FormConfig::TextField('debutVote') ?>"
               min="<?= date("Y-m-d H:i"); ?>" required>
    </p>
    <p>
        <label for="finVote">Date de fin des votes :</label>
        <input type="datetime-local" id="finVote" name="finVote"
               value="<?= FormConfig::TextField('finVote') ?>"
               min="<?= date("Y-m-d H:i"); ?>" required>
    </p>
    <input type="submit" name=previous value="Retour" class="nav" formnovalidate>
    <input type="submit" name=next value="Suivant" class="nav">
</form>

<?php
if (isset($message)) {
    echo "<div class=\"message\"><p><img src=\"../web/images/attention.png\" class=\"attention\"  alt=\"Warning\"> " . $message . "</p></div>";
} ?>
