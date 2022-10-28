<?php

$titre = htmlspecialchars($question->getTitre());
$debutEcriture = htmlspecialchars(($question->getCalendrier())->getDebutEcriture());
$finEcriture = htmlspecialchars(($question->getCalendrier())->getFinEcriture());
$debutVote = htmlspecialchars(($question->getCalendrier())->getDebutVote());
$finVote = htmlspecialchars(($question->getCalendrier())->getFinVote());
?>

<p>Titre : <?php echo $titre ?></p>

<div>
    <h2>Calendrier</h2>
    <p>Phase d'écriture : du <?= $debutEcriture ?> au <?= $finEcriture ?></p>
    <p>Phase de vote : du <?= $debutVote ?> au <?= $finVote ?></p>
</div>

<div>
    <h2>Auteurs</h2>
    <?php
    /*foreach ($question->getAuteur() as $auteur) {
        echo "<p> $auteur </p>";
    }*/
    ?>
</div>

<div>
    <h2>Votants</h2>
    <?php
    /*foreach ($_SESSION['votants'] as $votant) {
        echo "<p> $votant </p>";
    }*/
    ?>
</div>


<div>
    <h2>Sections</h2>
    <?php
    /*foreach ($_SESSION['Sections'] as $Section) {
        echo '<p>' . $Section["titre"] . ' : </p>';
        echo '<p>' . $Section["description"] . ' : </p>';
        echo '&nbsp';
    }*/
    ?>
</div>