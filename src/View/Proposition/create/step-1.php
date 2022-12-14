<?php

use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Controller\ControllerAccueil;
use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Model\Repository\PropositionRepository;


if(!isset($_GET['idQuestion'])){
    $idQuestion = $question->getId();
}else{
    $idQuestion = $_GET['idQuestion'];
}

if (isset($_GET['idProposition']) or isset($_SESSION[FormConfig::$arr]['idProposition']) ) {
    echo "<h1>Modification de la Proposition</h1>";
    if (isset($_GET['idProposition'])) {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if ($proposition == null) {
            ControllerAccueil::erreur();
        } else {
            $_SESSION[FormConfig::$arr]['idProposition'] = $_GET['idProposition'];
            FormConfig::initialiserSessionsProposition($proposition);
        }
    }

} else {
    echo "<h1>Création d'une Proposition</h1>";
}


if (isset($_SESSION[FormConfig::$arr]['idProposition'])){
    (new PropositionRepository())->select($_SESSION[FormConfig::$arr]['idProposition'])->getResponsable()->getIdentifiant();
}
if (isset($_POST['titre'])) {
    FormConfig::postSession();
    $_SESSION[FormConfig::$arr]['step'][1] = 1;
    if (!isset($_SESSION[FormConfig::$arr]['co-auteur'])) {
        $_SESSION[FormConfig::$arr]['co-auteur'] = array();
    }
    if (isset($_SESSION[FormConfig::$arr]['idProposition'])){
        if ((new PropositionRepository())->select($_SESSION[FormConfig::$arr]['idProposition'])->getResponsable()->getIdentifiant() != ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            FormConfig::redirect('index.php?controller=proposition&action=updated');
        }
    }
    FormConfig::redirect("index.php?controller=proposition&action=form&step=2&idQuestion=".$idQuestion);

}


?>



<h2>Titre : <?= $question->getTitre() ?></h2>
<h2>Description : <?= $question->getDescription() ?></h2>

<h3><i>* Veuillez remplir le formulaire ci-dessous, un titre pour votre proposition ainsi qu'un contenu pour chaque
        section.</i></h3>
<form method="post">

    <p>
        <label>Titre de votre proposition
            <input type="text" maxlength="500" id="titre_id" size="80" value="<?= FormConfig::TextField('titre')?>" name="titre">
        </label>
        <label>480 caractères maximum</label>
    </p>
    <!--<h2>Désigner les co-auteurs qui vous aideront à rédiger votre proposition :</h2>-->

    <?php
    $sections = $question->getSections();
    $i = 0;
    foreach ($sections as $section) {
        $i++;
        echo '<h2>Section n°' . $i . '</h2>';
        echo '<p>Titre : ' . $section->getTitre() . ' </p > ';
        echo '<p>Description : ' . $section->getDescription() . ' </p > ';
        echo '
    <p class="champ">
        <label for=contenu_id> Contenu</label > :
        <textarea name=contenu' . $section->getId() . ' id = contenu_id maxlength=1500 rows = 8 cols = 80 >'. FormConfig::TextField('contenu'.$section->getId()) .'</textarea >
        <label>1400 caractères maximum</label>
    </p> ';
    }
    ?>
    <input type="submit" name="next" value="Suivant" CLASS="nav">
</form>



