<?php
session_start();

use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Model\Repository\QuestionRepository;

if (isset($_POST['Titre'])) {
    FormConfig::postSession();
    $_SESSION['step'][1] = 1;
    if (!isset($_SESSION['responsables']) && !isset($_SESSION['votants'])) {
        $_SESSION['responsables'] = array();
        $_SESSION['votants'] = array();

    }
    FormConfig::redirect("index.php?controller=question&action=form&step=2");
}
if (isset($_GET['idQuestion'])) {
    echo "<h1>Modification de la question</h1>";
    $question = (new QuestionRepository())->select($_GET['idQuestion']);
    if ($question == null) {
        \App\Vote\Controller\ControllerAccueil::erreur();
    } else {
        FormConfig::initialiserSessions($question);
        $_SESSION['idQuestion'] = $question->getId();
    }

} else {
    echo "<h1>Création d'une question</h1>";
}
?>


<form method="post">

    <p>
        <label for="titre_id">Titre</label> :
        <input type="text" placeholder="L'oeuf ou la poule ? " size="78" maxlength="70" name="Titre" id="titre_id"
               value="<?= FormConfig::TextField('Titre') ?>"
               required/>
        <label for="max_id">70 caractères maximum</label>
    </p>
    <p class ="champ">
        <label for="description_id">Description : </label>
        <textarea id="description_id" maxlength="350" name="Description" rows="7" cols="50" required><?= FormConfig::TextField('Description'); ?></textarea>
        <label for="max_id">350 caractères maximum</label>
    </p>
    <p>
        <label for="nbSections_select">Nombre de sections</label>
        <select name="nbSections" id="nbSections_select">
            <?php
            for ($i = 1; $i <= 10; $i++) {
                echo "<option value=" . $i . FormConfig::DropDown("nbSections", $i) . " >" . $i . "</option>";

            } ?>
        </select>
    </p>

    <input type="submit" value="Suivant" class="nav"/>
</form>

<?php
if (isset($message)) {
    echo "<p><img src=\"/web/images/attention.png\" class=\"attention\" > " . $message . "</p>";
} ?>


