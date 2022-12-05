<div class="detail_question">
    <div class="infos">
        <h2>Titre : </h2>
        <p> <?= htmlspecialchars($question->getTitre()) ?></p>
        <h2>Description : </h2>
        <p> <?= htmlspecialchars($question->getDescription()) ?></p>


        <div class="responsables">
            <h2>Responsables : </h2>
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

        <div>
            <h2>Votants : </h2>
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


        <h2>Sections : </h2>
        <?php
        $i = 1;
        foreach ($sections as $Section) {
            echo '<h3> Section n° ' . $i . '</h3>';
            echo '<p> Titre : ' . htmlspecialchars($Section->getTitre()) . '</p>';
            echo '<p> Description : ' . htmlspecialchars($Section->getDescription()) . '</p>';
            echo '&nbsp';
            $i++;
        }
        ?>
        <h2>Date de création :</h2>
        <p>
            <?= htmlspecialchars($question->getcreation()); ?>
        </p>
    </div>
    <div class="calendrier">
        <h2>Calendrier : </h2>

        <?php
        $date = date("d/m/Y à H:i:s");
        $cercle = '<div id="cercle"></div>';
        if ($date < $question->getCalendrier()->getDebutEcriture()) {
            echo $cercle;
        }
        echo '<span class="vertical-line-petite"></span>';
        if ($date < $question->getCalendrier()->getDebutEcriture()) {
            echo $cercle;
        }
        ?>
        <p style="background: #CE16169B; color: white">Début d'écriture des propositions : <br>
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
        <p style="background: #CE16169B; color: white">Fin d'écriture des propositions : <br>
            <?= htmlspecialchars($question->getCalendrier()->getFinEcriture()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getFinEcriture() && $date < $question->getCalendrier()->getDebutVote()) {
            echo '<span class="vertical-line-petite" style="background: " ></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: "></span>';
        } else {
            echo '<span class="vertical-line" style="background: "></span>';
        }
        ?>
        <p style="background : rgba(65,112,56,0.76); color: white">Début des votes : <br>
            <?= htmlspecialchars($question->getCalendrier()->getDebutVote()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getDebutVote() && $date < $question->getCalendrier()->getFinVote()) {
            echo '<span class="vertical-line-petite" style="background: rgba(65,112,56,0.76);"></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: rgba(65,112,56,0.76);"></span>';
        } else {
            echo '<span class="vertical-line" style="background: rgba(65,112,56,0.76);"></span>';
        } ?>
        <p style="background: rgba(65,112,56,0.76); color: white">Fin des votes : <br>
            <?= htmlspecialchars($question->getCalendrier()->getFinVote()) ?></p>
        <?php
        echo '<span class="vertical-line-petite"></span>';
        if ($date > $question->getCalendrier()->getFinVote()) {
            echo $cercle;
        }
        ?>
    </div>
</div>




