<ul class="questions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;

    $i = 1;
    $calendrier = $question->getCalendrier();
    $date = date('d/m/Y à H:i:s');
    $aVote = Votant::aVote($propositions, $_SESSION['user']['id']);
    $aVoteURL = rawurlencode($aVote);
    foreach ($propositions as $proposition) {
        if ($aVote == $proposition->getId()) {
            echo '<h2>Vous avez voté pour cette proposition.</h2>';
        }
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '>' . $i . ' : ' . $titreHTML . '  </a>';
        if ($date >= $calendrier->getDebutVote() && $date < $calendrier->getFinVote()
            && ConnexionUtilisateur::estConnecte()
            && Votant::estVotant($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())
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
        if (CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getId()) ||
            $proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            echo '<a href = index.php?action=update&controller=proposition&idProposition=' .
                $proposition->getId() . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >';

        }
        if ($proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            echo '<a id="vote" href= index.php?action=create&controller=coauteur&idProposition=' .
                $idPropositionURL . '>Désigner des co-auteurs</a>';
        }
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo '</p>';
        $i++;
    }
    ?>
</ul>
