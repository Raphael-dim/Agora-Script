<ul class="selection">
    <li class="phases">
        <a href="index.php?action=readAll&selection=toutes&controller=question">Toutes</a>
    </li>
    <li class="phases">
        <a href="index.php?action=readAll&selection=ecriture&controller=question">En phase d'écriture
            <img class="crayon" src="..\web\images\crayon.png"
        </a>
    </li>
    <li class="phases">
        <a href="index.php?action=readAll&selection=vote&controller=question">En phase de vote
            <img class="vote" src="..\web\images\logo_vote.png"
        </a>
    </li>
    <li class="phases">
        <a href="index.php?action=readAll&selection=terminees&controller=question">Terminées
            <img class="terminee" src="..\web\images\terminee.png"
        </a>
    </li>
</ul>

<ul class="questions">

<?php

use App\Vote\Controller\ControllerAccueil;

$selection = "toutes";
if (isset($_GET['selection'])) {
    $selection = $_GET['selection'];
}


$date = date("Y/m/d H:i:s");

if ($selection == 'ecriture') {
    foreach ($questions as $question) {
        $calendrier = $question->getCalendrier();
        if ($calendrier->getDebutEcriture() > $date || $calendrier->getFinEcriture() < $date) {
            unset($questions[array_search($question, $questions)]);
        }
    }
} else if ($selection == 'vote') {
    foreach ($questions as $question) {
        $calendrier = $question->getCalendrier();
        if ($calendrier->getDebutVote() > $date || $calendrier->getFinVote() < $date) {
            unset($questions[array_search($question, $questions)]);
        }
    }

} else if ($selection == 'terminees') {
    foreach ($questions as $question) {
        $calendrier = $question->getCalendrier();
        if ($calendrier->getFinVote() >= $date) {
            unset($questions[array_search($question, $questions)]);
        }
    }
} else if ($selection != 'toutes') {
    ControllerAccueil::erreur();
}

    foreach ($questions as $question) {
        $idQuestionURL = rawurlencode($question->getId());
        $organisateur = htmlspecialchars($question->getOrganisateur()->getNom());
        $titreHTML = htmlspecialchars($question->getTitre());
        echo '<li class = "listes">
            <a href= index.php?action=read&controller=question&idQuestion=' .
            $idQuestionURL . '> ' . $titreHTML . ' : </a>
            <a href="">par ' . $organisateur . ' </a >
            <a href = index.php?action=update&controller=question&idQuestion=' .
            $idQuestionURL . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >
            <a href = index.php?action=delete&controller=question&idQuestion=' .
            $idQuestionURL . ' ><img class="delete" src = "..\web\images\delete.png" ></a >
            </li > ';
    }
    ?>
</ul>
