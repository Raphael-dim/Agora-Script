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
                <div class=proposition>
                    <a href= index.php?action=read&controller=proposition&idProposition=' .
                    $idPropositionURL . '> <h2>1. ' . $titreHTML . '</h2>   </a>
                    <br>
                    <h3>Nombre de votes : ' . $propositions[0]->getNbVotes() . '</h3>
                    <a href="" id = "auteur">par ' . $propositions[0]->getResponsable()->getIdentifiant() . ' </a >
                    <img class="imgPremier" src="images/premier.png">
                </div>
            </div>
             <div class="deuxTrois">
                 <div class="deuxieme">  
                    <div class=proposition>
                        <a href= index.php?action=read&controller=proposition&idProposition=' .
                        rawurlencode($propositions[1]->getId()) . '> <h2>2. ' . htmlspecialchars($propositions[1]->getTitre()) . '</h2>   </a>
                        <br>
                        <h3>Nombre de votes : ' . $propositions[1]->getNbVotes() . '</h3>
                        <a href="" id = "auteur">par ' . $propositions[1]->getResponsable()->getIdentifiant() . ' </a >
                        
                </div>  
                 </div>
                 
                 <div class="troisieme">  
                    <div class=proposition>
                        <a href= index.php?action=read&controller=proposition&idProposition=' .
                        rawurlencode($propositions[2]->getId()) . '> <h2>1. ' . htmlspecialchars($propositions[2]->getTitre()) . '</h2>   </a>
                        <br>
                        <h3>Nombre de votes : ' . $propositions[2]->getNbVotes() . '</h3>
                        <a href="" id = "auteur">par ' . $propositions[2]->getResponsable()->getIdentifiant() . ' </a >
                </div>  
                 </div>
             </div>
         </div>';


    foreach ($propositions as $proposition) {
        /*$idPropositionURL = rawurlencode($proposition->getId());
        $titreHTML = htmlspecialchars($proposition->getTitre());
        echo '<div class=proposition>';
        echo ' <a href= index.php?action=read&controller=proposition&idProposition=' .
            $idPropositionURL . '> <h2>' . $titreHTML . '</h2>   </a>';
        echo '<br > ';
        echo '<h3>Nombre de votes : ' . $proposition->getNbVotes() . '</h3>';
        $i++;
        echo '<a href="" id = "auteur">par ' . $proposition->getResponsable()->getIdentifiant() . ' </a >';
        echo '</div>';*/
    }
    ?>
</ul>
