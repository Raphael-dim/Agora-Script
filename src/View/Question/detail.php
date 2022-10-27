<p>Titre <?php echo $question->getTitre() ?></p>

<div>
    <h2>Calendrier</h2>
    <p>Phase d'écriture : du <?= $question->getCalendrier()->getDebutEcriture() ?> au <?= $question->getCalendrier()->getFinEcriture() ?></p>
    <p>Phase de vote : du <?= $question->getCalendrier()->getDebutVote() ?> au <?= $question->getCalendrier()->getFinEcriture() ?></p>
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


<div>
    <h2>Sections</h2>
    <?php
    if(is_array($sections)){
        foreach ($sections as $Section) {
            echo '<p>' . $Section->getTitre() . '</p>';
            echo '<p>' . $Section->getDescription() . '</p>';
            echo '&nbsp';
        }
    }else{
        echo '<p>' . $sections->getTitre() . '</p>';
        echo '<p>' . $sections->getDescription() . '</p>';
        echo '&nbsp';
    }

    ?>
</div>
