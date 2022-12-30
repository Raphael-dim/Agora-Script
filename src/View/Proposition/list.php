<div class="propositions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\Calendrier;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;
    use App\Vote\Model\Repository\VoteRepository;

    if ($question->getSystemeVote() == 'majoritaire') {
        $modeScrutin = 'Scrutin par jugement majoritaire';
        $message = 'Le scrutin majoritaire établit un \'vote médian\' pour chaque proposition, 
                    par défaut, la mention \'passable\' est sélectionnée.';
    }
    ?>
    <h2><?= $modeScrutin ?></h2>
    <p class="survol">
        <img class="imageAide" src="images/aide_logo.png" alt=""/>
        <span><?= $message ?></span>
    </p>
    <?php
    $i = 1;
    $peutVoter = false;
    $calendrier = $question->getCalendrier();
    if (sizeof($propositions) == 0) {
        echo '<h2>Il n\'y a pas de propositions pour cette question</h2>';
    }
    if ($question->getPhase() == 'vote' && ConnexionUtilisateur::estConnecte()
        && Votant::estVotant($votants, ConnexionUtilisateur::getLoginUtilisateurConnecte())
    ) {
        $votes = Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $peutVoter = true;
        $interval = (new DateTime(date("d-m-Y H:i")))->diff(new DateTime($calendrier->getFinVote(true)));
        echo '<h2>Il vous reste ' . Calendrier::diff($interval) . ' pour voter ! </h2>';
    }
    foreach ($propositions

             as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<div class=proposition>';
        echo ' <a href= "index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '"> <h2>' . $titreHTML . '</h2>   </a>';
        if ($peutVoter) {
            $vote = Votant::aVote($proposition, $votes);
            for ($val = 1; $val <= 6; $val++) {
                switch ($val) {
                    case 1 :
                        $attribut = 'À rejeter';
                        break;
                    case 2  :
                        $attribut = 'Insuffisant';
                        break;
                    case 3 :
                        $attribut = 'Passable';
                        break;
                    case 4:
                        $attribut = 'Assez bien';
                        break;
                    case 5 :
                        $attribut = 'Bien';
                        break;
                    case 6 :
                        $attribut = 'Très bien';
                        break;
                }
                if ($val <= $vote->getValeur()) {
                    echo '<a class=vote style="background:#a94442" 
                        href="index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '">
                        <img src=../web/images/coeur_logo.png alt="">';
                } else {
                    echo '<a class=vote 
                    href="index.php?controller=vote&action=choix&idProposition=' . $proposition->getId() . '&valeur=' . $val . '">
                        <img src=../web/images/coeur_logo.png alt="">
                        ';
                }
                echo '<span>'.$attribut.'</span></a>';
            }
            $nbVotes = htmlspecialchars($proposition->getNbVotes());
            $nbEtoiles = htmlspecialchars($proposition->getNbEtoiles());

            echo '<br > ';
            echo '<h3>Nombre de votes : ' . $nbVotes . '</h3>';
            $votesProposition = (new VoteRepository())->selectWhere($proposition->getId(), '*',
                'idProposition', 'Votes', 'valeurvote');
            if ($nbVotes > 0) {
                if ($nbVotes == 1) {
                    $median = $votesProposition[0];
                } else {
                    if (sizeof($votesProposition) % 2 == 0) {
                        $median = $votesProposition[(sizeof($votesProposition) / 2) - 1];
                    } else {
                        $median = $votesProposition[((sizeof($votesProposition) + 1) / 2) - 1];
                    }
                }
                echo '<h3>Moyenne des votes : ' . htmlspecialchars($nbEtoiles / $nbVotes) . '</h3>';
                echo '<h3>Médianne :  ' . htmlspecialchars($median->getValeur()) . '</h3>';
            }
        }


        if (CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getId()) ||
            $proposition->getIdResponsable() == ConnexionUtilisateur::getLoginUtilisateurConnecte() &&
            $question->getPhase() == 'ecriture') {

            echo ' <a href="index.php?action=update&controller=proposition&idProposition=' .
                rawurlencode($proposition->getId()) . '"><img class="modifier" src = "..\web\images\modifier.png" ></a ><br> ';
        }
        if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::getLoginUtilisateurConnecte() == $proposition->getIdResponsable() && $question->getPhase() == 'ecriture') {

            echo ' <a class="nav suppProp" 
            href=index.php?controller=proposition&action=delete&idProposition=' . rawurlencode($proposition->getId()) . '>Supprimer</a>';
        }
        $i++;
        echo '<a href="" >par ' . htmlspecialchars($proposition->getIdResponsable()) . ' </a >';
        echo '</div>';
    }
    ?>
</div>
