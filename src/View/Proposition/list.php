<ul class="questions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\Calendrier;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;

    $i = 1;
    $peutVoter = false;
    $calendrier = $question->getCalendrier();
    if ($question->getPhase() == 'vote' && ConnexionUtilisateur::estConnecte()
        && Votant::estVotant($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())
    ) {
        $peutVoter = true;
        $interval = (new DateTime(date("d-m-Y H:i")))->diff(new DateTime($calendrier->getFinVote(true)));
        echo '<h2>Il vous reste ' . Calendrier::diff($interval) . ' pour voter ! </h2>';
    }
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<form>';
        echo '<p class = "listes">
            <a href="index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '">' . $i . ' : ' . $titreHTML . '  </a>';
        if ($question->getPhase() == 'vote' && ConnexionUtilisateur::estConnecte()
            && Votant::estVotant($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())
            && $aVote != $proposition->getId()) {
            if (is_null($aVote)) {
                echo '<a id="vote" href="index.php?action=create&controller=vote&idproposition=' .
                    $idPropositionURL . '">Voter</a>';
            } else {
                echo '<a id="vote" href="index.php?action=update&controller=vote&idpropositionAnc=' .
                    $aVoteURL . '&idproposition=' . $idPropositionURL . '">Voter</a>';
            }
        } else if ($proposition->getId() == $aVote) {
            echo '<a id="vote" href="index.php?action=delete&controller=vote&idproposition=' .
                $idPropositionURL . '">Supprimer le vote</a>';
        }

        if(isset($_SESSION['user'])){
            if(CoAuteur::estCoAuteur($_SESSION['user']['id'],$proposition) || $proposition->getResponsable()->getIdentifiant() == $_SESSION['user']['id']){
                echo '<a href = "index.php?action=update&controller=proposition&step=1&idProposition=' .
                    $proposition->getId() . '"><img class="modifier" src = "..\web\images\modifier.png" ></a >';

            }
        }

        //if (CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition) ||
        //    $proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
        //echo ' < a href = index . php ? action = update & controller = proposition & idProposition = ' .
        //    $proposition->getId() . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a > ';

        //}
        //if ($proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
        //    echo '<a id = "vote" href = index.php?action=create&controller=coauteur&idProposition=' .
        //        $idPropositionURL . ' > Désigner des co - auteurs </a > ';
        echo '<br > ';
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        if(ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::getLoginUtilisateurConnecte()==$proposition->getResponsable()->getIdentifiant()) {
            echo '
<a class="nav suppProp" href=index.php?controller=proposition&action=delete&idProposition='.$proposition->getId().'>Supprimer</a>
            ';
        }
        echo ' </p> ';
        $i++;
    }

    ?>
    </form>

</ul>
