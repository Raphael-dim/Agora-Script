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
        echo '<div class=proposition>';
        echo ' <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '> <h2>' . $titreHTML . '</h2>   </a>';
        if ($peutVoter) {
            $vote = Votant::aVote($proposition, ConnexionUtilisateur::getLoginUtilisateurConnecte());
            if (!is_null($vote)) {
                for ($val = 1; $val <= $vote->getValeur(); $val++) {
                    echo '<a id=vote style="background:#a94442" href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img src=../web/images/coeur_logo.png alt=""></a>';
                }
                for ($val = $vote->getValeur() + 1; $val <= 5; $val++) {
                    echo '<a id=vote href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img src=../web/images/coeur_logo.png alt=""></a>';
                }
            } else {
                for ($val = 1; $val <= 5; $val++) {
                    echo '<a id=vote href=index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '>
                        <img src=../web/images/coeur_logo.png alt=""></a>';
                }
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
        echo '<h3>Nombre de votes : ' . $proposition->getNbVotes() . '</h3>';
        if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::getLoginUtilisateurConnecte() == $proposition->getResponsable()->getIdentifiant()) {
            echo ' <a class="nav suppProp" 
            href=index.php?controller=proposition&action=delete&idProposition=' . $proposition->getId() . '>Supprimer</a>';
        }
        $i++;
        echo '<a href="" id = "auteur">par ' . $proposition->getResponsable()->getIdentifiant() . ' </a >';
        echo '</div>';
    }
    ?>
</ul>
