<ul class="questions">
    <?php

    use App\Vote\Model\DataObject\Votant;

    $i = 1;
    $aVote = Votant::aVote($propositions, $_SESSION['user']['id']);
    foreach ($propositions as $proposition) {
        if ($aVote == $proposition->getId()) {
            echo '<h2>Vous avez déjà voté pour cette proposition.</h2>';
        }
        $idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<p class = "listes">
            <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '>' . $i . ' : ' . $titreHTML . '  </a>';
        if (isset($_SESSION['user']) && Votant::estVotant($question, $_SESSION['user']['id']) && $aVote == null) {
            echo '<a class="vote" href= index.php?action=create&controller=vote&idproposition=' .
                $idPropositionURL . '>Voter</a>';
        }
        echo 'Nombre de votes : ' . $proposition->getNbVotes();
        echo '</p>';
        $i = $i + 1;
    }
    ?>
</ul>
