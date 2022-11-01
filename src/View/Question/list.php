<?php

foreach ($questions as $question) {
    echo "<br>";
    $idQuestionURL = rawurlencode($question->getId());
    $titreHTML = htmlspecialchars($question->getTitre());
    echo '<li class = "listes"> 
            <a href= index.php?action=read&controller=question&idQuestion=' .
        $idQuestionURL . '> ' . $titreHTML . ' : </a> 
            <a href= index.php?action=update&controller=question&idQuestion=' .
        $idQuestionURL . '><img class="modifier" src="\web\images\modifier.png" ></a>
            <a href= index.php?action=delete&controller=question&idQuestion=' .
        $idQuestionURL . '><img class="delete" src="\web\images\delete.png" ></a>
            </li>';
}

?>
