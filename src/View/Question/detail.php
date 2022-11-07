<p>Titre : <?= $question->getTitre() ?></p>
<p>Description : <?= $question->getDescription() ?></p>

<div>
    <h2>Calendrier</h2>
    <p>Phase d'écriture : du <?= $question->getCalendrier()->getDebutEcriture() ?>
        au <?= $question->getCalendrier()->getFinEcriture() ?></p>
    <p>Phase de vote : du <?= $question->getCalendrier()->getDebutVote() ?>
        au <?= $question->getCalendrier()->getFinVote() ?></p>
</div>

<div>
    <h2>Auteurs</h2>
    <?php
    if(is_array($auteurs)){
        foreach ($auteurs as $auteur) {
            echo "<p>" . $auteur->getUtilisateur()->getIdentifiant() . "</p>";
        }
    }else{
        echo "<p>" . $auteurs->getUtilisateur()->getIdentifiant() . "</p>";
    }
    ?>
</div>

<div>
    <h2>Votants</h2>
    <?php
    if(is_array($votants)){
        foreach ($votants as $votant) {
            echo "<p>" . $votant->getUtilisateur()->getIdentifiant() . "</p>";
        }
    }else{
        echo "<p>" . $votants->getUtilisateur()->getIdentifiant() . "</p>";
    }

    ?>
</div>

<h2>Sections</h2>
<?php
$i = 1;
foreach ($sections as $Section) {
    echo '<h3> Section n° ' . $i . '</h3>';
    echo '<p>' . $Section->getTitre() . '</p>';
    echo '<p>' . $Section->getDescription() . '</p>';
    echo '&nbsp';
    $i++;
}
?>
<h2>Date de création :</h2>
<p>
    <?= $question->getcreation(); ?>
</p>



<div>
    <h2>Propositions</h2>
    <?php
    if(is_array($propositions)){
        foreach ($propositions as $proposition) {
            echo '<p>' . $proposition->getTitre() . '</p>';
            echo '<p>' . $proposition->getAuteur() . '</p>';
            echo '<p>' . $proposition->getContenu() . '</p>';
            echo '&nbsp';
        }
    }else if(!is_null($propositions)){
        echo '<p>' . $propositions->getTitre() . '</p>';
        echo '<p>' . $propositions->getAuteur() . '</p>';
        echo '<p>' . $propositions->getContenu() . '</p>';
        echo '&nbsp';
    }
    ?>
</div>

