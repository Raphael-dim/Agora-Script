<?php

use App\Vote\Model\Repository\PropositionRepository;
use \App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\DataObject\AbstractDataObject;
use App\Vote\Model\DataObject\Question;


?>

<h1>Détails de la proposition</h1>

<h2>Titre question : <?= $question->getTitre() ?></h2>
<h2>Description question : <?= $question->getDescription() ?></h2>

    <?php

    $i = 1;
    foreach ($sections as $section) {
        $propSection = (new PropositionSectionRepository())->selectWhere($section->getId(),'*','idsection','proposition_section');
        //var_dump($propSection);
        foreach ($propSection as $propSec) {
            $contenu = $propSec->getContenu();
        }
        echo '<h2>Section n°' . $i . '</h2>';
        echo '<p>Titre : ' . $section->getTitre() . ' </p > ';
        echo '<p>Description : ' . $section->getDescription() . ' </p > ';
        echo '
    <p>
        <label for=contenu_id> Contenu</label > :
        '. $contenu .'
    </p> ';
        $i=$i+1;
    }
    ?>



