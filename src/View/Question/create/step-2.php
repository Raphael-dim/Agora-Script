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
    FormConfig::postSession();
    for ($n = 1; $n <= $_SESSION[FormConfig::$arr]['nbCalendriers']; $n++) {
        $debutEcriture = $_POST['debutEcriture' . $n];
        $finEcriture = $_POST['finEcriture' . $n];
        $debutVote = $_POST['debutVote' . $n];
        $finVote = $_POST['finVote' . $n];
        var_dump($finVote);
        if ($debutEcriture >= $finEcriture) {
            MessageFlash::ajouter("warning", "La date de fin d'écriture doit être supérieure à la date de début d'écriture");
            Controller::redirect('index.php?controller=question&action=form&step=2');
        } else if ($debutVote >= $finVote) {
            MessageFlash::ajouter('warning', "La date de fin des votes doit être supérieure à la date de début des votes");
            Controller::redirect('index.php?controller=question&action=form&step=2');
        } else if ($debutVote <= $debutEcriture || $debutVote < $finEcriture) {
            MessageFlash::ajouter('warning', "La phase de vote doit commencer après la phase d'écriture");
            Controller::redirect('index.php?controller=question&action=form&step=2');
        } else if ($n < $_SESSION[FormConfig::$arr]['nbCalendriers'] && $_POST['finVote' . $n] > $_POST['debutEcriture' . $n + 1]) {
            MessageFlash::ajouter("warning", "Les phases doivent se succéder et ne peuvent être simultanées.");
            Controller::redirect('index.php?controller=question&action=form&step=2');
        }
    }

    FormConfig::postSession();
    $_SESSION[FormConfig::$arr]['step'][2] = 2;
    FormConfig::redirect("index.php?controller=question&action=form&step=3");


} else if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=1");
}

if (!isset($_SESSION[FormConfig::$arr]['nbCalendriers'])) {
    $_SESSION[FormConfig::$arr]['nbCalendriers'] = 1;
}

if (isset($_POST['ajoutPhase'])) {
    FormConfig::postSession();
    if ($_SESSION[FormConfig::$arr]['nbCalendriers'] < 7) {
        $_SESSION[FormConfig::$arr]['nbCalendriers']++;
    }
} else if (isset($_POST['supprimerPhase'])) {
    if ($_SESSION[FormConfig::$arr]['nbCalendriers'] > 1) {
        $_SESSION[FormConfig::$arr]['nbCalendriers']--;
    }
}
?>
<h1>Selection du calendrier</h1>

<form method="post">
    <!--    <input type="image" style="max-width: 30px" name="click" src="../web/images/add.png" alt="">-->
    <input type="submit" name="ajoutPhase" value="Ajouter une phase">
    <input type="submit" name="supprimerPhase" value="Supprimer une phase">

</form>

<form method="post" class="custom-form">
    <?php
    for ($n = 1; $n <= $_SESSION[FormConfig::$arr]['nbCalendriers']; $n++) {
        echo '<h2>Phase n°' . $n . '</h2>';
        echo ' <p>
        <label for="debutEcriture">Date de début d\'écriture des propositions :</label>
        <input type="datetime-local" id="debutEcriture" name="debutEcriture' . $n . '"
               value="' . FormConfig::TextField('debutEcriture' . $n) . '"
               min="' . date("Y-m-d H:i") . '" required>
    </p>
    <p>
        <label for="finEcriture">Date de fin d\'écriture des propositions :</label>
        <input type="datetime-local" id="finEcriture" name="finEcriture' . $n . '"
               value="' . FormConfig::TextField('finEcriture' . $n) . '"
               min="' . date("Y-m-d H:i") . '" required>
    </p>
    <p>
        <label for="debutVote">Date de début des votes :</label>
        <input type="datetime-local" id="debutVote" name="debutVote' . $n . '"
               value="' . FormConfig::TextField('debutVote' . $n) . '"
               min="' . date("Y-m-d H:i") . '" required>
    </p>
    <p>
        <label for="finVote">Date de fin des votes :</label>
        <input type="datetime-local" id="finVote" name="finVote' . $n . '"
               value="' . FormConfig::TextField("finVote" . $n) . '"
               min="' . date("Y-m-d H:i") . '" required>
    </p>';
    }
    ?>
    <input type="submit" name=previous value="Retour" id="precedent" class="nav" formnovalidate>
    <input type="submit" name=next value="Suivant" id="suivant" class="nav">

</form>

