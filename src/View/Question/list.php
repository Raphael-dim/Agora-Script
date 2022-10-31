<?php

foreach ($questions as $question) {
    echo "<br>";
    $idQuestionURL = rawurlencode($question->getId());
    $titreHTML = htmlspecialchars($question->getTitre());
    echo '<li class = "listes"> 
            <a href= index.php?action=read&controller=question&idQuestion=' .
        $idQuestionURL . '>Question : </a>' . $titreHTML . '
            <a href= index.php?action=update&controller=question&idQuestion='. $idQuestionURL . '>Modifer</a>
            </li>';
}

?>
