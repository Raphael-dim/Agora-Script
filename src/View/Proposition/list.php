<ul class="questions">
    <?php

    use App\Vote\Config\FormConfig;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Responsable;
    use App\Vote\Model\DataObject\Votant;
    use App\Vote\Model\Repository\CoAuteurRepository;

    $i = 1;
    $calendrier = $question->getCalendrier();
    $date = date('d/m/Y à H:i:s');
    if(isset($_SESSION['user'])){
        $aVote = Votant::aVote($propositions, $_SESSION['user']['id']);
    }else{
        $aVote = null;
    }


    $aVoteURL = rawurlencode($aVote);
    foreach ($propositions as $proposition) {
        if(!is_null($aVote)){
            if ($aVote == $proposition->getId()) {
                echo '<h2>Vous avez voté pour cette proposition.</h2>';
            }
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
        if(isset($_SESSION['user'])){
            if(CoAuteur::estCoAuteur($_SESSION['user']['id'],$proposition->getId()) || $proposition->getResponsable()->getIdentifiant() == $_SESSION['user']['id']){
                echo '<a href = index.php?action=update&controller=proposition&step=1&idProposition=' .
                    $proposition->getId() . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >';

            }
        }
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo '</p>';
        $i++;
    }
    ?>
</ul>