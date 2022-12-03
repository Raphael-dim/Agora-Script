
<?php
    if (isset($_GET['selection'])){
        if($_GET['selection'] == 'ecriture'){
            echo "<h1>Nos questions en phase d'<strong id ='color-orange'>écriture</strong></h1>";
        }
        else if($_GET['selection'] == 'vote'){
            echo "<h1>Nos questions en phase de <strong id ='color-yellow'>vote</strong></h1>";
        }
        else if($_GET['selection'] == 'terminees'){
            echo "<h1>Nos questions <strong id ='color-green'>terminées</strong></h1>";
        }
        else{
            echo "<h1>Consultez nos <strong id ='color-grey'>questions </strong> et trouvez des réponses</h1>";
        }
    }
    else{
        echo "<h1>Consultez nos <strong id ='color-grey'>questions </strong> et trouvez des réponses</h1>";
    }
?>

<div class="barreHaut">
    <form method="post" action="index.php?controller=question&action=readKeyword">
        <p>
            <label for="motclef"></label><input type="text" placeholder="" name="keyword" id="motclef" required/>
            <input type="image" alt="Submit" src="../web/images/search.png" value="Envoyer" class="search"/>
        </p>
    </form>
    <a class="bouton" href="index.php?action=create&controller=question">Créer votre question</a>
</div>

<div class="selection">
    <ul>
        <li class="phases" id  ="all">
            <a href="index.php?action=readAll&selection=toutes&controller=question">Toutes</a>
        </li>
        <li class="phases" id  ="ecriture">
            <a href="index.php?action=readAll&selection=ecriture&controller=question">En phase d'écriture</a>
        </li>
        <li class="phases" id  ="vote">
            <a href="index.php?action=readAll&selection=vote&controller=question">En phase de vote</a>
        </li>
        <li class="phases" id  ="termine">
            <a href="index.php?action=readAll&selection=terminees&controller=question">Terminées</a>
        </li>
    </ul>
</div>


<ul class="questions">
    <?php

    use App\Vote\Model\DataObject\CoAuteur;
    use App\Vote\Model\DataObject\Responsable;

    $date = date("Y-m-d H:i:s");
    foreach ($questions as $question) {
        $calendrier = $question->getCalendrier();
        $idQuestionURL = rawurlencode($question->getId());
        $organisateur = htmlspecialchars($question->getOrganisateur()->getIdentifiant());
        $titreHTML = htmlspecialchars($question->getTitre());


        if ($date < $calendrier->getDebutEcriture()) {
            echo '<li class="listes" id = "status_cree">';
        }
        else if ($date <$calendrier->getFinEcriture() && $date>$calendrier->getDebutEcriture()){
            echo '<li class="listes" id = "status_ecriture">';
        }
        else if ($date <$calendrier->getFinVote() && $date>$calendrier->getDebutVote()){
            echo '<li class="listes" id = "status_vote">';
        }
        else if ($date> $calendrier->getFinVote()){
            echo '<li class="listes" id = "status_termine">';
        }
        else{
            echo '<li class="listes" id = "status_attente">';
        }

        echo '
            <a class=titre href= index.php?action=read&controller=question&idQuestion=' .
            $idQuestionURL . '> ' . $titreHTML . ' </a>
            <a href="" id = "auteur">par ' . $organisateur . ' </a >';
        echo '<p id="description">'.$question->getDescription().'</p>';
        if ($calendrier->getDebutEcriture() > $date) {
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $organisateur) {
                echo '<div id="action" "><a href = index.php?action=update&controller=question&idQuestion=' .
                        $idQuestionURL . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >
                     <a href = index.php?action=delete&controller=question&idQuestion=' .
                        $idQuestionURL . ' ><img class="delete" src = "..\web\images\delete.png" ></a></div>';
            }
            echo '<p>Début de la phase d\'écriture : ' . $calendrier->getDebutEcriture() . '</p>';
        } else if ($date < $calendrier->getDebutVote()) {
            echo '<p>Début de la phase de vote : ' . $calendrier->getDebutVote() . '</p>';

        }


        if ($date > $calendrier->getDebutEcriture() && $date < $calendrier->getFinVote()) {
            echo '<a href = index.php?action=readAll&controller=proposition&idQuestion=' . $idQuestionURL . ' >Liste des propositions</a>';
        }
        if ($calendrier->getDebutEcriture() <= $date && $calendrier->getFinEcriture() >= $date &&
            isset($_SESSION['user']) && Responsable::estResponsable($question, $_SESSION['user']['id'])
            && !Responsable::aCreeProposition($question, $_SESSION['user']['id'])) {
            echo '<a href = index.php?action=create&controller=proposition&idQuestion=' . $idQuestionURL . '>Créer une proposition</a>';


        }

        echo '</li>';

    }
    ?>
</ul>
