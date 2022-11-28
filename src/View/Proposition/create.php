<?php

use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\DataObject\Question;


?>

<h1>Création d'une proposition</h1>

<h2>Titre : <?= $question->getTitre() ?></h2>
<h2>Description : <?= $question->getDescription() ?></h2>
<h3><i>* Veuillez remplir le formulaire ci-dessous, un titre pour votre proposition ainsi qu'un contenu pour chaque
        section.</i></h3>
<form method="post" action=index.php?controller=proposition&action=created&idQuestion=<?= $question->getId() ?>>

    <p>
        <label for="titre_id">Titre de votre proposition
            <input type="text" maxlength="500" size="80" value="" name="titre">
        </label>
        <label for="max_id">480 caractères maximum</label>
    </p>
    <?php
    $sections = $question->getSections();
    $i = 0;
    foreach ($sections as $section) {
        $i++;
        echo '<h2>Section n°' . $i . '</h2>';
        echo '<p>Titre : ' . $section->getTitre() . ' </p > ';
        echo '<p>Description : ' . $section->getDescription() . ' </p > ';
        echo '
    <p>
        <label for=contenu_id> Contenu</label > :
        <textarea name=contenu' . $section->getId() .' id = contenu_id maxlength=1500 rows = 8 cols = 80 required ></textarea >
        <label for=max_id>1400 caractères maximum</label>
    </p> ';
    }
    ?>
    <input type="submit" value="Suivant"/>
</form>



