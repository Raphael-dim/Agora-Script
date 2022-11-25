<?php
session_start();

use App\Vote\Config\FormConfig as FormConfig;


if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
} else if (isset($_POST['next'])) {
    if (isset($_SESSION['idQuestion'])) {
        FormConfig::redirect('index.php?controller=question&action=updated');
    } else {
        FormConfig::postSession();
        FormConfig::redirect("index.php?controller=question&action=created");
    }
}


extract($_SESSION);

$_SESSION['Sections'] = array();
$ct = 0;
foreach ($_SESSION as $key => $value) {
    if (str_starts_with($key, "titre")) {
        $_SESSION['Sections'][$ct]['titre'] = $value;
    }
    if (str_starts_with($key, "description")) {
        $_SESSION['Sections'][$ct]['description'] = $value;
        $ct++;
    }
}

if (count($_SESSION['Sections']) > $_SESSION['nbSections']) {
    for ($diff = count($_SESSION['Sections']) - $_SESSION['nbSections']; $diff > 0; $diff--) {
        unset($_SESSION['Sections'][count($_SESSION['Sections']) - 1]);
    }
}


?>

<p>Titre : <?= htmlspecialchars($Titre) ?></p>
<p>Description : <?= htmlspecialchars($Description) ?></p>


<div>
    <h2>Calendrier</h2>
    <p>Phase d'écriture : du <?= htmlspecialchars($debutEcriture) ?> au <?= htmlspecialchars($finEcriture) ?></p>
    <p>Phase de vote : du <?= htmlspecialchars($debutVote) ?> au <?= htmlspecialchars($finVote) ?></p>
</div>

<div>
    <h2>Responsables</h2>
    <?php
    foreach ($_SESSION['responsables'] as $responsable) {
        echo "<p> " . htmlspecialchars($responsable) . " </p>";
    }
    ?>
</div>

<div>
    <h2>Votants</h2>
    <?php
    foreach ($_SESSION['votants'] as $votant) {
        echo "<p> " . htmlspecialchars($votant) . " </p>";
    }
    ?>
</div>


<div>
    <h2>Sections</h2>
    <?php
    $i = 1;
    foreach ($_SESSION['Sections'] as $Section) {
        echo '<h3> Section n° ' . $i . '</h3>';
        echo '<p>Titre : ' . htmlspecialchars($Section["titre"]) . '  </p>';
        echo '<p>Description : ' . htmlspecialchars($Section["description"]) . '  </p>';
        echo '&nbsp';
        $i++;
    }
    ?>
</div>

<form method="post" class="nav">
    <input type="submit" name=previous value="Retour" formnovalidate/>
    <input type="submit" name=next value="Suivant"/>
</form>