<div class="detail_question">
    <div class="infos">
        <h1><strong  id ='color-grey'>Titre</strong></h1>
        <p> <?= htmlspecialchars($question->getTitre()) ?></p>
        <h1><strong  id ='color-grey'>Description</strong></h1>
        <p> <?= htmlspecialchars($question->getDescription()) ?></p>

        <div id = "participants">
            <div id = "responsables">
                <h1><strong  id ='color-yellow'>Responsables</strong></h1>
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
                <h1><strong  id ='color-yellow'>Votants</strong></h1>
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
        <h1><strong  id ='color-orange'>Sections</strong></h1>
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
        <h1><strong  id ='color-green'>Calendrier</strong></h1>

        <div class="légende">
            <p style="background: #FFC55C; color: white">Phase d'écriture des propositions</p>
            <p style="background: #FDE541; color: white">Phase de vote</p>
        </div>
        <?php
        $date = date("d/m/Y à H:i:s");
        $cercle = '<div id="cercle"></div>';
        if ($date < $question->getCalendrier()->getDebutEcriture()) {
            echo $cercle;
        }

        if ($date < $question->getCalendrier()->getDebutEcriture()) {
            echo $cercle;
        }
        ?>
        <p class = "cal"  id = "ecriture_debut"><?= htmlspecialchars($question->getCalendrier()->getDebutEcriture()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getDebutEcriture() && $date < $question->getCalendrier()->getFinEcriture()) {
            echo '<span class="vertical-line-petite" style="background: orange"></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: orange"></span>';
        } else {
            echo '<span class="vertical-line" style="background: orange"></span>';
        }
        ?>
        <p class = "cal"  id = "ecriture_fin"><?= htmlspecialchars($question->getCalendrier()->getFinEcriture()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getFinEcriture() && $date < $question->getCalendrier()->getDebutVote()) {
            echo '<span class="vertical-line-petite" style="background:grey " ></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background:grey "></span>';
        } else {
            echo '<span class="vertical-line" style="background:grey "></span>';
        }
        ?>
        <p class = "cal"  id = "vote_debut"><?= htmlspecialchars($question->getCalendrier()->getDebutVote()) ?></p>
        <?php
        if ($date > $question->getCalendrier()->getDebutVote() && $date < $question->getCalendrier()->getFinVote()) {
            echo '<span class="vertical-line-petite" style="background: yellow"></span>';
            echo $cercle;
            echo '<span class="vertical-line-petite" style="background: yellow"></span>';
        } else {
            echo '<span class="vertical-line" style="background: yellow"></span>';
        }
        ?>
        <p class = "cal" id = "vote_fin"><?= htmlspecialchars($question->getCalendrier()->getFinVote()) ?></p>
        <?php
        echo '<span class="vertical-line-petite" style="background:grey "></span>';
        if ($date > $question->getCalendrier()->getFinVote()) {
            echo $cercle;
        }
        ?>
    </div>
</div>




