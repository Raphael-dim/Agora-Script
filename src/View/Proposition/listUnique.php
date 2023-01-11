<link href="css/ListePropositions.css" rel="stylesheet">
<div class="propositions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\Calendrier;
    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Votant;
    use App\Vote\Model\Repository\VoteRepository;

    $modeScrutin = 'Scrutin par vote unique';
    $message = 'Vous pouvez voter pour une seule et unique proposition, la proposition qui emporte le plus
        de voix est désignée gagnante.';
    ?>
    <h2 class="custom_titre"><?= $modeScrutin ?></h2>
    <p class="survol">
        <img class="imageAide" src="images/aide_logo.png" alt="aide"/>
        <span class="messageInfo"><?= $message ?></span>
    </p>
    <?php
    if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant() &&
        ($question->getPhase() == 'entre' || $question->getPhase() == 'debut') && $question->aPassePhase()) {
        $organisateurRole = 'Vous êtes responsable pour cette question multiphase';
        $messageOrganisateur = 'Vous pouvez éliminer les propositions les moins attractives. 
                Par défaut, elles sont triées par nombre de votes, si vous éliminez
            une proposition, vous éliminez aussi celles qui ont un nombre de votes inférieurs.';
        ?>
        <h2 class="custom_titre"><?= $organisateurRole ?></h2>
        <p class="survol">
            <img class="imageAide" src="images/aide_logo.png" alt="aide"/>
            <span class="messageInfo"><?= $messageOrganisateur ?></span>
        </p>
        <?php
        echo '<h2></h2>';
    }
    $i = 1;
    $peutVoter = false;
    $calendrier = $question->getCalendrier();
    if ($question->getPhase() == 'vote' && ConnexionUtilisateur::estConnecte()
        && Votant::estVotant($votants, ConnexionUtilisateur::getLoginUtilisateurConnecte())
    ) {
        $votes = Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $peutVoter = true;
        $interval = (new DateTime(date("d-m-Y H:i")))->diff(new DateTime($calendrier->getFinVote(true)));
        echo '<h2 class="custom_titre">Il vous reste ' . Calendrier::diff($interval) . ' pour voter ! </h2>';
    }
    foreach ($propositions as $proposition) {

        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        if ($proposition->isEstEliminee()) {
            echo '<div style="background: rgba(58,69,75,0.55)" class="proposition shadow-effect eliminee">';
            echo '<h1 class="estEliminee"><strong class="custom_strong color-grey " style="color: rgba(37,47,47,0.66);" >Eliminé</strong></h1>';
        } else {
            echo '<div class="proposition shadow-effect">';
        }
        echo ' <a href= "index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '"> <h2 style="font-size: 22px" class = "Titre_proposition ">' . $titreHTML . '</h2>   </a>';
        if ($peutVoter && !$proposition->isEstEliminee()) {
            $vote = Votant::aVote($proposition, $votes);
            if (is_null($vote)) {
                echo '<form method="get" action="../web/index.php">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="controller" value="vote">
                <input type="hidden" name="idProposition" value="' . rawurlencode($proposition->getId()) . '">
                <input type="submit" value="Voter" class="nav">
                    </form>';
            } else {
                echo '<form method="get" action="../web/index.php">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="vote">
                <input type="hidden" name="idProposition" value="' . rawurlencode($proposition->getId()) . '">
                <input type="submit" value="Supprimer le vote" class="nav">
                    </form>';
            }

            echo '<br > ';
        }
        $nbVotes = htmlspecialchars($proposition->getNbEtoiles());
        echo '<h3>Nombre de votes : ' . $nbVotes . '</h3>';
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant() &&
            ($question->getPhase() == 'entre' || $question->getPhase() == 'debut') && $question->aPassePhase()) {
            if ($proposition->isEstEliminee()) {
                echo '<p><a class="link-custom" href="index.php?controller=proposition&action=annulerEliminer&idProposition=' . $idPropositionURL . '">Annuler l\'élimination</a><br></p>';
            } else {
                echo '<p><a class="link-custom" href="index.php?controller=proposition&action=eliminer&idProposition=' . $idPropositionURL . '">Eliminer</a><br></p>';
            }
        }
        if ((CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getId()) ||
                $proposition->getIdResponsable() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) &&
            $question->getPhase() == 'ecriture') {

            echo ' <p><a href="index.php?action=update&controller=proposition&idProposition=' .
                rawurlencode($proposition->getId()) . '"><img class="modifier" src = "../web/images/modifier.png"  alt="modifier"></a > ';
        }

        if (ConnexionUtilisateur::estConnecte() && ConnexionUtilisateur::getLoginUtilisateurConnecte() == $proposition->getIdResponsable() && $question->getPhase() != 'vote') {
            echo ' <a style="margin-left: 20px" href="index.php?controller=proposition&action=delete&idProposition=' . rawurlencode($proposition->getId()) . '"><img class="delete" src = "../web/images/delete.png"  alt="supprimer"></a>';
        }
        echo '</p>';
        $i++;
        echo '<p><a style="float: left" class="link-custom" href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($proposition->getIdResponsable()) . '" >
        par ' . htmlspecialchars($proposition->getIdResponsable()) . ' </a>';
        echo '<a style="float:right" class = "link-custom" href= "index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '">Lire plus</a></p>';
        echo '</div>';
    }
    ?>
</div>
