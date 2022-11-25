<div class="barreHaut">
    <a class="rechercher" href="">Rechercher une question</a>
    <a class="creer" href="index.php?action=create&controller=question">Créer votre question</a>
</div>

<div class="selection">
    <input type="checkbox" name="filtres"/>  <label for="filtres">filtres</label>
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
    foreach ($questions as $question) {
        $idQuestionURL = rawurlencode($question->getId());
        $organisateur = htmlspecialchars($question->getOrganisateur()->getNom());
        $titreHTML = htmlspecialchars($question->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=question&idQuestion=' .
            $idQuestionURL . '> ' . $titreHTML . ' : </a>
            <a href="">par ' . $organisateur . ' </a >';
            echo '<a href = index.php?action=update&controller=question&idQuestion=' .
                $idQuestionURL . ' ><img class="modifier" src = "..\web\images\modifier.png" ></a >
            <a href = index.php?action=delete&controller=question&idQuestion=' .

            $idQuestionURL . ' ><img class="delete" src = "..\web\images\delete.png" ></a >
            ';
            echo '<a href = index.php?action=create&controller=vote&idQuestion=' .
                $idQuestionURL . ' ><img class="vote" src = "..\web\images\vote.png" ></a >
            
            </p > ';

    }
    ?>
</ul>
