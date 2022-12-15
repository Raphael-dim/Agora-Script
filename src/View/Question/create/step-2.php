<?php

use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Controller\Controller;
use App\Vote\Lib\MessageFlash;

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
    if ($debutEcriture >= $finEcriture) {
        MessageFlash::ajouter("warning", "La date de fin d'écriture doit être supérieure à la date de début d'écriture");
        Controller::redirect('index.php?controller=question&action=form&step=2');
    } else if ($debutVote >= $finVote) {
        MessageFlash::ajouter('warning', "La date de fin des votes doit être supérieure à la date de début des votes");
        Controller::redirect('index.php?controller=question&action=form&step=2');

    } else if ($debutVote <= $debutEcriture || $debutVote < $finEcriture) {
        MessageFlash::ajouter('warning', "La phase de vote doit commencer après la phase d'écriture");
        Controller::redirect('index.php?controller=question&action=form&step=2');

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

<form method="post" class = "custom-form">
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
    <input type="submit" name=previous value="Retour" id="precedent" class="nav" formnovalidate>
    <input type="submit" name=next value="Suivant" id="suivant" class="nav">
</form>

