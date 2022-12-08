<ul class="questions">
    <?php

    $i = 1;
    if (sizeof($propositions) == 0) {
        echo '<h2>Il n\'y a pas encore de propositions.</h2>';
    }
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '>' . $i . ' : ' . $titreHTML . '  </a>';
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo '</p>';
        $i = $i + 1;
    }
    ?>
</ul>
