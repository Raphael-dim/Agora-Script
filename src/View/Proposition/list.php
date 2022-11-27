
<ul class="propositions">
    <?php
    $i=1;
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
        $idPropositionURL . '>'.$i.' : ' . $titreHTML . '  </a>
         <a href= index.php?action=create&controller=vote&idproposition=' .
            $idPropositionURL . '><img class="vote" src="..\web\images\button_vote.png"></a></p>'
        ;
        $i = $i + 1;
    }
    ?>
</ul>
