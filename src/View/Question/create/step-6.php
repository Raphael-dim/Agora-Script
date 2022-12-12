<?php

use App\Vote\Config\FormConfig as FormConfig;


if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
} else if (isset($_POST['next'])) {
    if (isset($_SESSION[FormConfig::$arr]['idQuestion'])) {
        FormConfig::redirect('index.php?controller=question&action=updated');
    } else {
        FormConfig::postSession();
        FormConfig::redirect("index.php?controller=question&action=created");
    }
}


extract($_SESSION[FormConfig::$arr]);

$_SESSION[FormConfig::$arr]['Sections'] = array();
$ct = 0;
foreach ($_SESSION[FormConfig::$arr] as $key => $value) {
    if (str_starts_with($key, "titre")) {
        $_SESSION[FormConfig::$arr]['Sections'][$ct]['titre'] = $value;
    }
    if (str_starts_with($key, "description")) {
        $_SESSION[FormConfig::$arr]['Sections'][$ct]['description'] = $value;
        $ct++;
    }
}

if (count($_SESSION[FormConfig::$arr]['Sections']) > $_SESSION[FormConfig::$arr]['nbSections']) {
    for ($diff = count($_SESSION[FormConfig::$arr]['Sections']) - $_SESSION[FormConfig::$arr]['nbSections']; $diff > 0; $diff--) {
        unset($_SESSION[FormConfig::$arr]['Sections'][count($_SESSION[FormConfig::$arr]['Sections']) - 1]);
    }
}


?>
<h1><strong class='color-grey'><?= htmlspecialchars($Titre) ?></strong></h1>
<h1><strong class='color-grey'><?= htmlspecialchars($Description) ?></strong></h1>


<div>
    <h1><strong class='color-green'>Calendrier</strong></h1>
    <p>Phase d'écriture : du <?= htmlspecialchars($debutEcriture) ?> au <?= htmlspecialchars($finEcriture) ?></p>
    <p>Phase de vote : du <?= htmlspecialchars($debutVote) ?> au <?= htmlspecialchars($finVote) ?></p>
</div>

<div>
    <h1><strong class='color-yellow'>Responsables</strong></h1>
    <?php
    foreach ($_SESSION[FormConfig::$arr]['responsables'] as $responsable) {
        echo "<p> " . htmlspecialchars($responsable) . " </p>";
    }
    ?>
</div>

<div>
    <h1><strong class='color-yellow'>Votants</strong></h1>
    <?php
    foreach ($_SESSION[FormConfig::$arr]['votants'] as $votant) {
        echo "<p> " . htmlspecialchars($votant) . " </p>";
    }
    ?>
</div>


<div>
    <h1><strong class='color-orange'>Sections</strong></h1>
    <?php
    $i = 1;
    foreach ($_SESSION[FormConfig::$arr]['Sections'] as $Section) {
        echo '<h3> Section n° ' . $i . '</h3>';
        echo '<p>Titre : ' . htmlspecialchars($Section["titre"]) . '  </p>';
        echo '<p>Description : ' . htmlspecialchars($Section["description"]) . '  </p>';
        echo '&nbsp;';
        $i++;
    }
    ?>
</div>

<form method="post" class="nav">
    <input type="submit" name=previous value="Retour" id="precedent" formnovalidate>
    <input type="submit" name=next value="Suivant" id="suivant">
</form>