<link href="css/ListeQuestion.css" rel="stylesheet">
<?php
if (isset($_GET['selection'])) {
    if ($_GET['selection'] == 'ecriture') {
        echo "<h1>Nos questions en phase d'<strong class ='color-orange'>écriture</strong></h1>";
    } else if ($_GET['selection'] == 'vote') {
        echo "<h1>Nos questions en phase de <strong class ='color-yellow'>vote</strong></h1>";
    } else if ($_GET['selection'] == 'terminees') {
        echo "<h1>Nos questions <strong class ='color-green'>terminées</strong></h1>";
    } else {
        echo "<h1>Consultez nos <strong class ='color-grey'>questions </strong> et trouvez des réponses</h1>";
    }
} else {
    echo "<h1>Consultez nos <strong class ='color-grey'>questions </strong> et trouvez des réponses</h1>";
}
?>

<div class="barreHaut">
    <form method="post" action="index.php?controller=question&action=readKeyword">
        <p>
            <label for="motclef"></label><input placeholder="Rechercher une question" type="text" placeholder=""
                                                name="keyword" id="motclef"
                                                required>
            <input type="image" alt="Submit" src="../web/images/search.png" class="search">
        </p>
    </form>
    <a class="bouton" href="index.php?action=create&controller=question">Créer votre question</a>
</div>

<div class="selection">
    <ul>
        <li class="phases" id="all">
            <a href="index.php?action=readAll&selection=toutes&controller=question">Toutes</a>
        </li>
        <li class="phases" id="ecriture">
            <a href="index.php?action=readAll&selection=ecriture&controller=question">En phase d'écriture</a>
        </li>
        <li class="phases" id="vote">
            <a href="index.php?action=readAll&selection=vote&controller=question">En phase de vote</a>
        </li>
        <li class="phases" id="termine">
            <a href="index.php?action=readAll&selection=terminees&controller=question">Terminées</a>
        </li>
    </ul>
</div>


<ul class="questions">
    <?php

    use App\Vote\Lib\ConnexionUtilisateur;
    use App\Vote\Model\DataObject\Calendrier;
    use App\Vote\Model\DataObject\Responsable;

    $date = date("d-m-Y H:i");
    foreach ($questions as $question) {
        $calendrier = $question->getCalendrier();
        $idQuestionURL = rawurlencode($question->getId());
        $organisateur = htmlspecialchars($question->getOrganisateur()->getIdentifiant());
        $titreHTML = htmlspecialchars($question->getTitre());


        if ($question->getPhase() == 'debut') {
            echo '<li class="listes status_cree shadow-effect">';
        } else if ($question->getPhase() == 'ecriture') {
            echo '<li class="listes status_ecriture shadow-effect">';
        } else if ($question->getPhase() == 'vote') {
            echo '<li class="listes status_vote shadow-effect">';
        } else if ($question->getPhase() == 'fini') {
            echo '<li class="listes status_termine shadow-effect">';
        } else {
            echo '<li class="listes status_attente shadow-effect">';
        }

        echo ' <p class=titre> ' . $titreHTML . ' </p>
            <a href="index.php?action=read&controller=utilisateur&idUtilisateur=' . rawurlencode($question->getOrganisateur()->getIdentifiant()) . '" class = "auteur link-custom">par ' . $organisateur . ' </a >';
        echo '<p class="description">' . htmlspecialchars($question->getDescription()) . '</p>';
        if ($question->getPhase() == 'debut' || ConnexionUtilisateur::estAdministrateur()) {
            if (ConnexionUtilisateur::estAdministrateur() || (ConnexionUtilisateur::estConnecte() &&
                    ConnexionUtilisateur::getLoginUtilisateurConnecte() == $organisateur)) {
                echo '<div class="action" style="display: inline"><a href ="index.php?action=update&controller=question&idQuestion=' .
                    $idQuestionURL . '"><img class="modifier" src = "../web/images/modifier.png"  alt="modifier"></a >
                     <a href ="index.php?action=delete&controller=question&idQuestion=' .
                    $idQuestionURL . '"><img class="delete" src = "../web/images/delete.png"  alt="supprimer"></a></div>';
            }
            $interval = (new DateTime($date))->diff(new DateTime($calendrier->getDebutEcriture(true)));
            if ($question->getPhase() == 'debut') {
                echo '<p class="debut" >Début de la phase d\'écriture dans : ' . Calendrier::diff($interval) . '</p>';
            }
        }
        if ($question->getPhase() == 'ecriture' || $question->getPhase() == 'entre') {
            $interval = (new DateTime($date))->diff(new DateTime($calendrier->getDebutVote(true)));
            echo '<p class="debut">Début de la phase de vote dans : ' . Calendrier::diff($interval) . '</p>';
        }
        if ($question->getPhase() == 'vote') {
            $interval = (new DateTime($date))->diff(new DateTime($calendrier->getFinVote(true)));
            echo '<p class="debut"> Fin de la phase de vote dans : ' . Calendrier::diff($interval) . '</p>';
        }
        if ($question->aPassePhase() || ($question->getPhase() != 'debut' && $question->getPhase() != 'fini')) {
            echo '<a class = "link-custom" style = "position:absolute" href ="index.php?action=readAll&controller=proposition&idQuestion=' . $idQuestionURL . '">Liste des propositions</a>';
        }
        if ($question->getPhase() == 'ecriture' && ConnexionUtilisateur::estConnecte() &&
            Responsable::estResponsable($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())
            && !Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            echo '<a class = "link-custom" style = "position:absolute; margin-top:25px" href ="index.php?action=create&controller=proposition&idQuestion=' . $idQuestionURL . '">Créer une proposition</a>';
        }
        if ($question->getPhase() == 'fini') {
            echo '<a class = "link-custom" style = "position:absolute"  href="index.php?controller=question&action=result&idQuestion=' . $idQuestionURL . '">Page de résultat</a>';
        }
        echo '<a class = "link-custom" style = "position:absolute; bottom:10px; right:5px;" href="index.php?action=read&controller=question&idQuestion=' .
            $idQuestionURL . '">Lire plus</a>';
        echo ' </li > ';
    }
    ?>
</ul>
