<?php

//require "../src/View/Utilisateurs/search.php";
foreach ($questions as $question) {
    echo "<br>";
    $idQuestionURL = rawurlencode($question->getId());
    $titreHTML = htmlspecialchars($question->getTitre());
    echo '<li class = "listes"> 
            <a href= frontController.php?action=read&idQuestion=' .
        $idQuestionURL . '>Question : </a>' . $titreHTML . '
            </li>';
}

?>
