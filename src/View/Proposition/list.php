
<ul class="propositions">
    <?php
    $i=1;
    foreach ($propositions as $proposition) {
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
        $idPropositionURL . '>'.$i.' : ' . $titreHTML . '  </a>';
        foreach ($votants as $votant) {
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $votant->getId()) {
                echo '<a href= index.php?action=create&controller=vote&idproposition=' .
                    $idPropositionURL . '><img class="vote" src="..\web\images\button_vote.png"></a>';
            }
        }
        //echo $proposition->getNbVotes();
        echo '</p>';
        $i = $i + 1;
    }
    ?>
</ul>
