<?php

use App\Vote\Config\FormConfig as FormConfig;


if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
} else if (isset($_POST['next'])) {
    if (isset($_SESSION[FormConfig::$arr]['idQuestion'])) {
        FormConfig::redirect('index.php?controller=question&action=updated');
    } else {
        FormConfig::postSession();
        FormConfig::redirect("index.php?controller=question&action=created");
    }
}


extract($_SESSION[FormConfig::$arr]);
var_dump($_SESSION[FormConfig::$arr]['systemeVote']);
$_SESSION[FormConfig::$arr]['Sections'] = array();
$ct = 0;
foreach ($_SESSION[FormConfig::$arr] as $key => $value) {
    if (str_starts_with($key, "titre")) {
        $_SESSION[FormConfig::$arr]['Sections'][$ct]['titre'] = $value;
    }
    if (str_starts_with($key, "description")) {
        $_SESSION[FormConfig::$arr]['Sections'][$ct]['description'] = $value;
        $ct++;
    }
}

if (count($_SESSION[FormConfig::$arr]['Sections']) > $_SESSION[FormConfig::$arr]['nbSections']) {
    for ($diff = count($_SESSION[FormConfig::$arr]['Sections']) - $_SESSION[FormConfig::$arr]['nbSections']; $diff > 0; $diff--) {
        unset($_SESSION[FormConfig::$arr]['Sections'][count($_SESSION[FormConfig::$arr]['Sections']) - 1]);
    }
}


?>
<div class="detail_question ">
    <div class="infos">
        <div class="detail_question ">
            <h1><strong class='color-blue'>Titre</strong></h1>
            <p><?= htmlspecialchars($Titre) ?></p>
            <h1><strong class='color-blue'>Description</strong></h1>
            <p><?= htmlspecialchars($Description) ?></p>
        </div>
        <div id="participants" class="info">
            <h1><strong class='color-yellow'>Participants</strong></h1>
            <div id="responsables">
                <h1><strong class='color-yellow'>Responsables</strong></h1>
                <?php
                foreach ($_SESSION[FormConfig::$arr]['responsables'] as $responsable) {
                    echo "<p> " . htmlspecialchars($responsable) . " </p>";
                }
                ?>
            </div>

            <div id="votants">
                <h1><strong class='color-yellow'>Votants</strong></h1>
                <?php
                foreach ($_SESSION[FormConfig::$arr]['votants'] as $votant) {
                    echo "<p> " . htmlspecialchars($votant) . " </p>";
                }
                ?>
            </div>
        </div>


        <div class="sections  info">
                <h1><strong class='color-orange'>Sections</strong></h1>
                <?php
                $i = 1;
                foreach ($_SESSION[FormConfig::$arr]['Sections'] as $Section) {
                    echo '<div class = "section">';
                    echo '<h3 style = "color:black"> Section n° ' . $i . '</h3>';
                    echo '<p>Titre : ' . htmlspecialchars($Section["titre"]) . '  </p>';
                    echo '<p>Description : ' . htmlspecialchars($Section["description"]) . '  </p>';
                    echo '&nbsp;';
                    echo '</div>';
                    $i++;
                }
                ?>
            </div>
            <div class="info">
                <div class="calendrier">
                    <h1><strong class='color-green'>Calendrier</strong></h1>
                    <?php
                    for ($i = 1; $i <= $_SESSION[FormConfig::$arr]['nbCalendriers']; $i++) {
                        echo '<span class="vertical-line-petite" style="background:grey "></span>';
                        $cercle = '<div id="cercle"></div>';
                        ?>

                        <p style="background: #CE16169B; color: white; padding: 6px" class="cal" id="ecriture_debut">
                            Début d'écriture des propositions : <br>
                            <?= htmlspecialchars(FormConfig::TextField('debutEcriture' . $i)) ?></p>
                        <!--        <span class="vertical-line-petite" style="background: #CE16169B"></span>-->
                        <span class="vertical-line" style="background: #CE16169B"></span>
                        <p style="background: #CE16169B; color: white; padding: 6px" class="cal" id="ecriture_fin">
                            Fin d'écriture des propositions : <br>
                            <?= htmlspecialchars(FormConfig::TextField('finEcriture' . $i)) ?></p>
                        <span class="vertical-line" style="background:grey "></span>
                        <p style="background : rgba(65,112,56,0.76); color: white; padding: 6px" class="cal"
                           id="vote_debut">Début des votes :
                            <br>
                            <?= htmlspecialchars(FormConfig::TextField('debutVote' . $i)) ?></p>
                        <span class="vertical-line" style="background: rgba(65,112,56,0.76);"></span>
                        <p style="background: rgba(65,112,56,0.76); color: white; padding: 6px" class="cal"
                           id="vote_fin">
                            Fin des votes : <br>
                            <?= htmlspecialchars(FormConfig::TextField('finVote' . $i)) ?></p>
                        <span class="vertical-line-petite" style="background:grey "></span>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <form method="post" class="nav">
        <input type="submit" name=previous value="Retour" id="precedent" formnovalidate>
        <input type="submit" name=next value="Suivant" id="suivant">
    </form>