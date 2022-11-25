<h2>Sections : </h2>
<?php
$sections = $question->getSections();
//$propositions = $question->getPropositions();

$i = 1;
foreach ($sections as $Section) {
    echo '<h3> Section n° ' . $i . '</h3>';
    echo '<p> Titre : ' . htmlspecialchars($Section->getTitre()) . '</p>';
    echo '<p> Description : ' . htmlspecialchars($Section->getDescription()) . '</p>';
    echo '&nbsp';
    $i++;
}
?>
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
