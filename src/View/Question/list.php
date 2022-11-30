<div class="barreHaut">
    <form method="post" action="index.php?controller=question&action=readKeyword">
            <p>
                <label for="motclef"></label><input type="text" placeholder="" name="keyword" id="motclef" required/>
                <input type="image" alt = "Submit" src="../web/images/search.png" value="Envoyer" class = "search"/>
            </p>
    </form>
    <a class="bouton" href="index.php?action=create&controller=question">Créer votre question</a>
</div>

<div class="selection">
    <input type="checkbox" name="filtres"/> <label for="filtres">filtres</label>
    <ul>
        <li class="phases">
            <a href="index.php?action=readAll&selection=toutes&controller=question">Toutes</a>
        </li>
        <li class="phases">
            <a href="index.php?action=readAll&selection=ecriture&controller=question">En phase d'écriture</a>
        </li>
        <li class="phases">
            <a href="index.php?action=readAll&selection=vote&controller=question">En phase de vote</a>
        </li>
        <li class="phases">
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
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=question&idQuestion=' .
            $idQuestionURL . '> ' . $titreHTML . ' : </a>
            <a href="">par ' . $organisateur . ' </a >';
        if ($calendrier->getDebutEcriture() > $date) {
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $organisateur) {
                echo '<a href = index.php?action=update&controller=question&idQuestion=' .
                    $idQuestionURL . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >

            <a href = index.php?action=delete&controller=question&idQuestion=' .
                    $idQuestionURL . ' ><img class="delete" src = "..\web\images\delete.png" ></a >';
            }
            echo ' | Début de la phase d\'écriture : ' . $calendrier->getDebutEcriture();
        } else if ($date < $calendrier->getDebutVote()) {
            echo ' | Début de la phase de vote : ' . $calendrier->getDebutVote();

        }


        if ($date > $calendrier->getDebutEcriture() && $date < $calendrier->getFinVote()) {
            echo '<a href = index.php?action=readAll&controller=proposition&idQuestion=' . $idQuestionURL . ' >Liste des propositions</a>';
        }
        if ($calendrier->getDebutEcriture() <= $date && $calendrier->getFinEcriture() >= $date &&
            isset($_SESSION['user']) && Responsable::estResponsable($question, $_SESSION['user']['id'])
            && !Responsable::aCreeProposition($question, $_SESSION['user']['id'])) {
            echo '<a href = index.php?action=create&controller=proposition&idQuestion=' . $idQuestionURL . '>Créer une proposition</a>';



        }

        echo '</p>';

    }
    ?>
</ul>
