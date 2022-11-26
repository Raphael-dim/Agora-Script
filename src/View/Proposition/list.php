
<ul class="propositions">
    <?php
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
        $idPropositionURL . '> ' . $titreHTML . ' : </a>';

    }
    ?>
</ul>
