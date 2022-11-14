<h2>Titre : </h2>
<p> <?= htmlspecialchars($question->getTitre()) ?></p>
<h2>Description : </h2>
<p> <?= htmlspecialchars($question->getDescription()) ?></p>

<div>
    <h2>Calendrier : </h2>
    <p>Phase d'écriture : du <?= htmlspecialchars($question->getCalendrier()->getDebutEcriture()) ?>
        au <?= htmlspecialchars($question->getCalendrier()->getFinEcriture()) ?></p>
    <p>Phase de vote : du <?= htmlspecialchars($question->getCalendrier()->getDebutVote()) ?>
        au <?= htmlspecialchars($question->getCalendrier()->getFinVote()) ?></p>
</div>

<div>
    <h2>Responsables : </h2>
    <?php
    if (is_array($responsables)) {
        foreach ($responsables as $responsable) {
            echo "<p>" . htmlspecialchars($responsable->getIdentifiant()) . "</p>";
        }
    } else {
        echo "<p>" . htmlspecialchars($responsables->getIdentififant()) . "</p>";
    }
    ?>
</div>

<div>
    <h2>Votants : </h2>
    <?php
    if (is_array($votants)) {
        foreach ($votants as $votant) {
            echo "<p>" . htmlspecialchars($votant->getIdentifiant()) . "</p>";
        }
    } else {
        echo "<p>" . htmlspecialchars($votants->getIdentifiant()) . "</p>";
    }

    ?>
</div>

<h2>Sections : </h2>
<?php
$i = 1;
foreach ($sections as $Section) {
    echo '<h3> Section n° ' . $i . '</h3>';
    echo '<p> Titre : ' . htmlspecialchars($Section->getTitre()) . '</p>';
    echo '<p> Description : ' . htmlspecialchars($Section->getDescription()) . '</p>';
    echo '&nbsp';
    $i++;
}
?>
<h2>Date de création :</h2>
<p>
    <?= htmlspecialchars($question->getcreation()); ?>
</p>


<div>
    <h2>Propositions</h2>
    <p>Pas encore dispo</p>
    <!--<?php
    if (is_array($propositions)) {
        foreach ($propositions as $Section) {
            echo '<p>' . htmlspecialchars($Section->getTitre()) . '</p>';
            echo '<p>' . $propositions->getAuteur() . '</p>';
            echo '<p>' . $Section->getContenu() . '</p>';
            echo '&nbsp';
        }
    } else {
        echo '<p>' . $propositions->getTitre() . '</p>';
        echo '<p>' . $propositions->getAuteur() . '</p>';
        echo '<p>' . $propositions->getContenu() . '</p>';
        echo '&nbsp';
    }
    ?>//-->
</div>

