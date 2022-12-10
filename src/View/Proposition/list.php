<ul class="questions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;

    $i = 1;
    $calendrier = $question->getCalendrier();
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<form>';
        echo '<p class = "listes">
                <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '>' . $i . ' : ' . $titreHTML . '  </a>';
        if ($question->getPhase() == 'vote' && ConnexionUtilisateur::estConnecte()
            && Votant::estVotant($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())
        ) {
            $vote = Votant::aVote($proposition, ConnexionUtilisateur::getLoginUtilisateurConnecte());
            if (!is_null($vote)) {
                for ($val = 1; $val <= $vote->getValeur(); $val++) {
                    echo '<a id=vote style="background:#a94442" href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img src=../web/images/coeur_logo.png alt=""></a>';
                }
                for ($val = $vote->getValeur() + 1; $val <= 5; $val++) {
                    echo '<a id=vote href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img style="filter: invert(100%)" src=../web/images/coeur_logo.png alt=""></a>';
                }
            } else {
                for ($val = 1; $val <= 5; $val++) {
                    echo '<a id=vote href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img src=../web/images/coeur_logo.png alt=""></a>';
                }
            }
        }

        if (CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition) ||
            $proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            //echo ' < a href = index . php ? action = update & controller = proposition & idProposition = ' .
            //    $proposition->getId() . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a > ';

        }
        if ($proposition->getResponsable()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            echo '<a id = "vote" href = index.php?action=create&controller=coauteur&idProposition=' .
                $idPropositionURL . ' > Désigner des co - auteurs </a > ';
        }
        echo '<br > ';
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo ' </p> ';
        $i++;
    }
    ?>
    </form>

</ul>
