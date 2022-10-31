<p>Titre : <?= $question->getTitre() ?></p>
<p>Description : <?= $question->getDescription() ?></p>

<div>
    <h2>Calendrier</h2>
    <p>Phase d'écriture : du <?= $question->getCalendrier()->getDebutEcriture() ?>
        au <?= $question->getCalendrier()->getFinEcriture() ?></p>
    <p>Phase de vote : du <?= $question->getCalendrier()->getDebutVote() ?>
        au <?= $question->getCalendrier()->getFinEcriture() ?></p>
</div>

<div>
    <h2>Auteurs</h2>
    <p>Pas encore dispo</p>
    <!--<?php
    foreach ($_SESSION['auteurs'] as $auteur) {
        echo "<p> $auteur </p>";
    }
    ?>//-->
</div>

<div>
    <h2>Votants</h2>
    <p>Pas encore dispo</p>
    <!--<?php
    foreach ($_SESSION['votants'] as $votant) {
        echo "<p> $votant </p>";
    }
    ?>//-->
</div>

<h2>Sections</h2>
<?php
if (is_array($sections)) {
    $i = 1;
    foreach ($sections as $Section) {
        echo '<h3> Section n° ' . $i . '</h3>';
        echo '<p>' . $Section->getTitre() . '</p>';
        echo '<p>' . $Section->getDescription() . '</p>';
        echo '&nbsp';
        $i++;
    }
} else {
    echo '<h3> Section n°1</h3>';
    echo '<p>' . $sections->getTitre() . '</p>';
    echo '<p>' . $sections->getDescription() . '</p>';
    echo '&nbsp';
}

?>
<h2>Date de création :</h2>
<p>
    <?= $question->getcreation(); ?>
</p>

