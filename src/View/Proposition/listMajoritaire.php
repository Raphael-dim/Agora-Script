<div class="propositions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\Calendrier;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;
    use App\Vote\Model\Repository\VoteRepository;

    if ($question->getSystemeVote() == 'majoritaire') {
        $methodeTrie = 'en fonction de leur vote médian';
        $inferieur = 'un vote médian';
        $modeScrutin = 'Scrutin par jugement majoritaire (médiane) ';
        $message = 'Le scrutin majoritaire établit un \'vote médian\' pour chaque proposition, 
                    par défaut, la mention \'passable\' est sélectionnée.<br>
                    Vous devez choisir une mention pour chaque proposition.';
    } else if ($question->getSystemeVote() == 'valeur') {
        $methodeTrie = 'par moyenne de votes';
        $inferieur = 'une moyenne de votes';
        $modeScrutin = 'Scrutin par jugement majoritaire (moyenne) ';
        $message = 'Ce système de vote établit une moyenne de vote pour chaque proposition, 
                    par défaut, la mention \'passable\' est sélectionnée.<br>
                    Vous devez choisir une mention pour chaque proposition.';
    }
    if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant() &&
        ($question->getPhase() == 'entre' || $question->getPhase() == 'debut') && $question->aPassePhase()) {
        $organisateurRole = 'Vous êtes responsable pour cette question multiphase';
        $messageOrganisateur = 'Vous pouvez éliminer les propositions les moins attractives. 
                Par défaut, elles sont triées ' . $methodeTrie . ', si vous éliminez
            une proposition, vous éliminez aussi celles qui ont ' . $inferieur . ' inférieur.';
        ?>
        <h2><?= $organisateurRole ?></h2>
        <p class="survol">
            <img class="imageAide" src="images/aide_logo.png" alt=""/>
            <span class="messageInfo"><?= $messageOrganisateur ?></span>
        </p>
        <?php
        echo '<h2></h2>';
    }

    ?>
    <h2><?= $modeScrutin ?></h2>
    <p class="survol">
        <img class="imageAide" src="images/aide_logo.png" alt=""/>
        <span class="messageInfo"><?= $message ?></span>
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
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        if ($proposition->isEstEliminee()) {
            echo '<div style="background: #000e17" class=proposition>';
            echo '<h3>(Eliminé)</h3>';
        } else {
            echo '<div class=proposition>';
        }
        echo ' <a href= "index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '"> <h2>' . $titreHTML . '</h2>   </a>';
        if ($peutVoter && !$proposition->isEstEliminee()) {
            $vote = Votant::aVote($proposition, $votes, 'majoritaire');
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
                echo '<span style="font-size: 18px">' . $attribut . '</span></a>';
            }
        }
        $nbEtoiles = htmlspecialchars($proposition->getNbEtoiles());
        echo '<br > ';

        /*
            On récupère les votes dans la vue pour éviter de faire plusieurs appel à la base de donnée
        dans le controller. La méthode ReadAll() n'est pas appelée par le controllerVote lors du vote / modification de vote.
        Si c'était le cas, on devrait refaire un appel à la base de donnée pour récupérer la question, les propositions et les votants
        à chaque interaction avec le système de vote.

        */


        $votesProposition = (new VoteRepository())->selectWhere($proposition->getId(), '*',
            'idProposition', 'Votes', 'valeurvote');
        echo '<h3>Nombre de votes : ' . sizeof($votesProposition) . '</h3>';
        if (sizeof($votesProposition) > 0) {
            if ($question->getSystemeVote() == 'majoritaire') {
                if (sizeof($votesProposition) == 1) {
                    $median = $votesProposition[0];
                } else {
                    if (sizeof($votesProposition) % 2 == 0) {
                        $median = $votesProposition[(sizeof($votesProposition) / 2) - 1];
                    } else {
                        $median = $votesProposition[((sizeof($votesProposition) + 1) / 2) - 1];
                    }
                }
                echo '<h3>Vote médian :  ' . htmlspecialchars(number_format($median->getValeur(), 3)) . '</h3>';
            } else {
                echo '<h3>Moyenne des votes : ' . htmlspecialchars(number_format($nbEtoiles / sizeof($votesProposition), 3),) . '</h3>';
            }
        }


        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant() &&
            ($question->getPhase() == 'entre' || $question->getPhase() == 'debut') && $question->aPassePhase()) {
            if ($proposition->isEstEliminee()) {
                echo '<p><a href="index.php?controller=proposition&action=annulerEliminer&idProposition=' . $idPropositionURL . '">Annuler l\'élimination</a><br></p>';
            } else {
                echo '<p><a href="index.php?controller=proposition&action=eliminer&idProposition=' . $idPropositionURL . '">Eliminer</a><br></p>';
            }
        }

        if (CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getId()) ||
            $proposition->getIdResponsable() == ConnexionUtilisateur::getLoginUtilisateurConnecte() &&
            $question->getPhase() == 'ecriture') {

            echo ' <p><a href="index.php?action=update&controller=proposition&idProposition=' .
                rawurlencode($proposition->getId()) . '"><img class="modifier" src = "..\web\images\modifier.png" ></a ><br></p> ';
        }

        if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::getLoginUtilisateurConnecte() == $proposition->getIdResponsable() && $question->getPhase() == 'ecriture') {
            echo ' <p><a class="nav suppProp" 
            href=index.php?controller=proposition&action=delete&idProposition=' . rawurlencode($proposition->getId()) . '>Supprimer</a><br></p>';
        }
        $i++;
        echo '<p><a href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($proposition->getIdResponsable()) . '" >par ' . htmlspecialchars($proposition->getIdResponsable()) . ' </a></p>';
        echo '</div>';
    }
    ?>
</div>
