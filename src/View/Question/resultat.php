<link href="css/ListePropositions.css" rel="stylesheet">


<?php

use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\Question;

function valeurToQualitatif(int $val): string{
    switch ($val) {
        case 1 :
            return 'À rejeter';
        case 2  :
            return 'Insuffisant';
        case 3 :
            return 'Passable';
        case 4:
            return 'Assez bien';
        case 5 :
            return 'Bien';
        case 6 :
            return 'Très bien';
    }
}

$i = 1;
function getResultat(Proposition $proposition, Question $question): string
{
    if ($question->getSystemeVote() == 'majoritaire') {
        $voteMedian = number_format($proposition->getVoteMedian(), 3);
        return '<h3>Vote médian : ' . valeurToQualitatif($voteMedian) . '</h3>';
    } else if ($question->getSystemeVote() == 'unique') {
        $nbr = number_format($proposition->getNbVotes(), 3);
        return '<h3>Nombre de votes : ' . $nbr . '</h3>';
    } else if ($question->getSystemeVote() == 'valeur') {
        $moyenne = number_format($proposition->getMoyenneVote(), 3);
        return '<h3>Moyenne des votes : ' . $moyenne . '</h3>';
    }
    return "";
}

$idPropositionURL = rawurlencode($propositions[0]->getId());
$titreHTML = htmlspecialchars($propositions[0]->getTitre());
?>
<div class="podium">
    <div class="premier">
        <div class="proposition shadow-effect">
            <a href="index.php?action=read&controller=proposition&idProposition=<?= $idPropositionURL ?>">
                <h2 class = "Titre_proposition"><?= $titreHTML ?></h2></a>
            <?php
            echo getResultat($propositions[0], $question) . ' <a class="link-custom" href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($propositions[0]->getIdResponsable()) . '"
     >par ' . htmlspecialchars($propositions[0]->getIdResponsable()) . ' </a>
                    <img class="medaille" src="images/premier.png" alt="">
                </div>
            </div>';
            if (sizeof($propositions) >= 2) {
            echo '<div class="deuxTrois">
                     <div class="deuxieme">  
                        <div class="proposition shadow-effect" >
                            <a href="index.php?action=read&controller=proposition&idProposition=' .
                rawurlencode($propositions[1]->getId()) . '"> <h2>' . htmlspecialchars($propositions[1]->getTitre()) . '</h2>   </a>';
            ?>
            <?= getResultat($propositions[1], $question) ?>
            <a class="link-custom"
               href="index.php?action=read&controller=utilisateur&idUtilisateur=<?= rawurlencode($propositions[0]->getIdResponsable()) ?>"
            > par <?= htmlspecialchars($propositions[1]->getIdResponsable()) ?> </a>
            <img class="medaille" src="images/deuxieme.png" alt="">

        </div>
    </div>
    <?php
    }
    if (sizeof($propositions) >= 3) {
        echo '
    <div class="troisieme">
        <div class="proposition shadow-effect">
            <a href="index.php?action=read&controller=proposition&idProposition=' .
            rawurlencode($propositions[2]->getId()) . '"><h2> ' . htmlspecialchars($propositions[2]->getTitre())
            . ' </h2></a>';
        echo getResultat($propositions[2], $question) . '
            <a class="link-custom" href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($propositions[0]->getIdResponsable()) . '"
            > par ' . htmlspecialchars($propositions[2]->getIdResponsable()) . ' </a>
            <img class="medaille" src="images/troisieme.png" alt="">
        </div>
    </div>
    ';
    }
    echo '
</div></div> ';

    echo '
<div class="propositionsResultat"> ';
    for ($i = 3; $i < count($propositions); $i++) {
        $idPropositionURL = rawurlencode($propositions[$i]->getId());
        $titreHTML = htmlspecialchars($propositions[$i]->getTitre());
        echo '
    <div class="proposition shadow-effect"> ';
        echo ' <a href="index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '"><h2> ' . $i + 1 . ' . ' . $titreHTML . '</h2></a> ';
        echo getResultat($propositions[$i], $question);
        echo '<a class="link-custom" href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($propositions[0]->getIdResponsable()) . '"
        > par ' . htmlspecialchars($propositions[$i]->getIdResponsable()) . ' </a> ';
        echo '
    </div>
    ';
    }
    echo '
</div> ';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        confetti({
            spread: 360,
            particleCount: 450,
            origin: {
                x: 0.5,
                y: 0.2
            }
        })
        setTimeout(function () {
            confetti({
                spread: 120,
                particleCount: 250,
                origin: {
                    x: 0.2,
                    y: 1.2
                }
            })

            confetti({
                spread: 120,
                particleCount: 250,
                origin: {
                    x: 0.8,
                    y: 1.2
                }
            })
        }, 1000);

    </script>
    <script src="js/jquery-3.4.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
