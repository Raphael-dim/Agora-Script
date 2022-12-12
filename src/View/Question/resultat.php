<ul class="propositions">
    <?php

    $i = 1;
    if (sizeof($propositions) == 0) {
        echo '<h2>Il n\'y a pas encore de propositions.</h2>';
    }

    $idPropositionURL = rawurlencode($propositions[0]->getId());
    $titreHTML = htmlspecialchars($propositions[0]->getTitre());

    echo'<div>
            <div class="premier">
                 <div class="divDePremier">
                        <p class = "listes">
                            <a href= index.php?action=read&controller=proposition&idProposition=' .
                            $idPropositionURL . '>' . 1 . ' : ' . $titreHTML . '  </a>
                            Nombre de votes :' . $propositions[0]->getNbVotes().'
                        </p>
                 </div>
             </div>
             <div class="deuxTrois">
                 <div class="deuxieme">  
                    <p class = "listes">
                        <a href= index.php?action=read&controller=proposition&idProposition=' .
                        rawurlencode($propositions[1]->getId()) . '>' . 2 . ' : ' . htmlspecialchars($propositions[1]->getTitre()) . '  </a>
                        Nombre de votes :' . $propositions[1]->getNbVotes().'
                    </p>   
                 </div>
                 
                 <div class="troisieme">  
                    <p class = "listes">
                        <a href= index.php?action=read&controller=proposition&idProposition=' .
                        rawurlencode($propositions[2]->getId()) . '>' . 3 . ' : ' . htmlspecialchars($propositions[2]->getTitre()) . '  </a>
                        Nombre de votes :' . $propositions[2]->getNbVotes().'
                    </p>    
                 </div>
             </div>
         </div>';

    ?>
</ul>
