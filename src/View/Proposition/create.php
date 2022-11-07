<?php
session_start();
use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\DataObject\Question;

if (isset($_POST['Titre'])){
    FormConfig::postSession();
    //FormConfig::redirect("index.php?action=created&controller=proposition");
}

?>

<h1>Création d'une proposition</h1>


<form method="post" action="index.php?action=created&controller=proposition">
    <p>
        <label for="question_select">Question : </label>
        <select name="question" id="question_select">

            <?php
            $_SESSION['questions'] = (new QuestionRepository())->selectAll();
            foreach ($_SESSION['questions'] as $question) {
                echo "<option value=" . $question->getTitre() . " >" . $question->getTitre() . "</option>";
            } ?>
        </select>
    </p>
    <p>
        <label for="titre_id">Titre</label> :
        <input type="text" placeholder="Ex : " name="Titre" id="titre_id" value = "<?=FormConfig::TextField('Titre')?>" required/>
    </p>
    <p>
        <label for="contenu_id">Contenu</label> :
        <textarea  name="contenu" id="contenu_id" rows="8" cols="80" required>Réponse à la question</textarea>
    </p>

    <input type="submit" value="Suivant"/>
</form>

<?php
if (isset($message)) {
    echo "<p><img src=\"/web/images/attention.png\" class=\"attention\" > " . $message . "</p>";
} ?>


