<div class="detail_question">
    <div class="infos">
        <div class = "question info">
        <h1>Titre</h1>
        <p> <?= htmlspecialchars($question->getTitre()) ?></p>
        <h1>Description</h1>
        <p> <?= htmlspecialchars($question->getDescription()) ?></p>

        </div>

        <div id = "participants" class="info">
            <div id = "responsables">
                <h1><strong  class ='color-yellow'>Responsables</strong></h1>
                <?php
                if (is_array($responsables)) {
                    foreach ($responsables as $responsable) {
                        echo "<p>" . htmlspecialchars($responsable->getIdentifiant()) . "</p>";
                    }
                } else {
                    echo "<p>" . htmlspecialchars($responsables->getIdentififant()) . "</p>";
                }
                ?>
            </div>

            <div id = "votants">
                <h1><strong  class ='color-yellow'>Votants</strong></h1>
                <?php
                if (is_array($votants)) {
                    foreach ($votants as $votant) {
                        echo "<p>" . htmlspecialchars($votant->getIdentifiant()) . "</p>";
                    }
                } else {
                    echo "<p>" . htmlspecialchars($votants->getIdentifiant()) . "</p>";
                }

                ?>
            </div>
        </div>
        <div class = "sections  info">
        <h1><strong  class ='color-orange'>Sections</strong></h1>

        <?php
        $i = 1;
        foreach ($sections as $Section) {
            echo '<h3> Section n° ' . $i . '</h3>';
            echo '<p> Titre : ' . htmlspecialchars($Section->getTitre()) . '</p>';
            echo '<p> Description : ' . htmlspecialchars($Section->getDescription()) . '</p>';
            echo '&nbsp;';
            $i++;
        }
        ?>
        <h2>Date de création :</h2>
        <p>
            <?= htmlspecialchars($question->getcreation()); ?>
        </p>
        </div>
    <div class="calendrier  info">
        <h1><strong  class ='color-green'>Calendrier</strong></h1>



        <?php
        $date = date("d/m/Y à H:i:s");
        $cercle = '<div id="cercle"></div>';
        if ($date < $question->getCalendrier()->getDebutEcriture()) {
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: grey"></span>';
        }
        ?>

        <p style="background: #CE16169B; color: white"  class = "cal"  id = "ecriture_debut" >Début d'écriture des propositions : <br>
            <?= htmlspecialchars($question->getCalendrier()->getDebutEcriture()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getDebutEcriture() && $date < $question->getCalendrier()->getFinEcriture()) {
            echo '<span class="vertical-line-petite" style="background: rgba(206,22,22,0.61)"></span>';
            echo $cercle;
            ?>

            <?php
            echo '<span class="vertical-line-petite" style="background: #CE16169B"></span>';
        } else {
            echo '<span class="vertical-line" style="background: #CE16169B"></span>';
        } ?>
        <p style="background: #CE16169B; color: white" class = "cal"  id = "ecriture_fin">Fin d'écriture des propositions : <br>
            <?= htmlspecialchars($question->getCalendrier()->getFinEcriture()) ?></p>

        <?php
        if ($date > $question->getCalendrier()->getFinEcriture() && $date < $question->getCalendrier()->getDebutVote()) {
            echo '<span class="vertical-line-petite" style="background:grey " ></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background:grey "></span>';
        } else {
            echo '<span class="vertical-line" style="background:grey "></span>';
        }
        ?>

        <p style="background : rgba(65,112,56,0.76); color: white" class = "cal"  id = "vote_debut">Début des votes : <br>
            <?= htmlspecialchars($question->getCalendrier()->getDebutVote()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getDebutVote() && $date < $question->getCalendrier()->getFinVote()) {
            echo '<span class="vertical-line-petite" style="background: rgba(65,112,56,0.76);"></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: rgba(65,112,56,0.76);"></span>';
        } else {
            echo '<span class="vertical-line" style="background: rgba(65,112,56,0.76);"></span>';
        } ?>
        <p style="background: rgba(65,112,56,0.76); color: white"  class = "cal" id = "vote_fin">Fin des votes : <br>
            <?= htmlspecialchars($question->getCalendrier()->getFinVote()) ?></p>

      
        <?php
        echo '<span class="vertical-line-petite" style="background:grey "></span>';
        if ($date > $question->getCalendrier()->getFinVote()) {
            echo $cercle;
        }
        ?>
    </div>
</div>




