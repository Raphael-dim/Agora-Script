<ul class="questions">
    <?php

    use App\Vote\Model\DataObject\Votant;

    $i = 1;
    $calendrier = $question->getCalendrier();
    $date = date("Y-m-d H:i:s");
    $aVote = Votant::aVote($propositions, $_SESSION['user']['id']);
    $aVoteURL = rawurlencode($aVote);
    if (sizeof($propositions) == 0){
        echo '<h2>Il n\'y a pas encore de propositions.</h2>';
    }
    foreach ($propositions as $proposition) {
        if ($aVote == $proposition->getId()) {
            echo '<h2>Vous avez voté pour cette proposition.</h2>';
        }
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '>' . $i . ' : ' . $titreHTML . '  </a>';
        if ($date >= $calendrier->getDebutVote() && $date < $calendrier->getFinVote() &&
            isset($_SESSION['user']) && Votant::estVotant($question, $_SESSION['user']['id'])
            && $aVote != $proposition->getId()) {
            if (is_null($aVote)) {
                echo '<a id="vote" href= index.php?action=create&controller=vote&idproposition=' .
                    $idPropositionURL . '>Voter</a>';
            } else {
                echo '<a id="vote" href= index.php?action=update&controller=vote&idpropositionAnc=' .
                    $aVoteURL . '&idproposition=' . $idPropositionURL . '>Voter</a>';
            }
        } else if ($proposition->getId() == $aVote) {
            echo '<a id="vote" href= index.php?action=delete&controller=vote&idproposition=' .
                $idPropositionURL . '>Supprimer le vote</a>';
        }
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo '</p>';
        $i = $i + 1;
    }
    ?>
</ul>
