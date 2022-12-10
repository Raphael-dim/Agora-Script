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
echo '<h1>Titre de la proposition : ' . $proposition->getTitre() . '</h1>';
    echo'<div id = "participants" class="detail_question">';
    echo'<div>';
        echo'<h1><strong  id ="color-yellow">Auteur</strong></h1>';

            if (!is_null($proposition->getResponsable())) {
                echo "<p>" . htmlspecialchars($proposition->getResponsable()->getIdentifiant()) . "</p>";
            }

        echo '</div>';
    echo'<div id="votants">';
        echo'<h1><strong  id ="color-yellow">Co-Auteurs</strong></h1>';
        if(!is_null($coAuts)) {
            if (is_array($coAuts)) {
                foreach ($coAuts as $coAut) {
                    echo "<p>" . htmlspecialchars($coAut->getUtilisateur()->getIdentifiant()) . "</p>";
                }
            } else {
                echo "<p>" . htmlspecialchars($coAuts->getIdentifiant()) . "</p>";
            }
        }else{
            echo "<p>Aucun co-auteur</p>";
        }
            echo '</div>';
        echo'</div>';
    echo '</div>';

$propSection = (new PropositionSectionRepository())->selectWhere($proposition->getId(), '*', 'idproposition', 'Proposition_section');
foreach ($sections as $section) {
    $contenu = $propSection;
    echo '<h2>Section n°' . $i . '</h2>';
    echo '<p>Titre : ' . $section->getTitre() . ' </p > ';
    echo '<p>Description : ' . $section->getDescription() . ' </p > ';
    echo'<div id = "Responsables">';
    echo '
    <p>
        <label for=contenu_id> Contenu</label > :
        ' . $contenu[$i-1]->getContenu() . '
    </p> ';
    $i = $i + 1;
}
?>



