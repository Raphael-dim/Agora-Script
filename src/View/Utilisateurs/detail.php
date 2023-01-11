<?php

use App\Vote\Lib\ConnexionUtilisateur;

?>
    <link href="css/profile.css" rel="stylesheet">
    <div class="barre_utilisateur">
        <img id = "picture" src = "../web/images/profile_pic.jpg">
        <div id = "infos_utilisateur">
            <h1 id = "nom"> <?= htmlspecialchars($utilisateur->getPrenom()) ?> <?= htmlspecialchars($utilisateur->getNom()) ?></h1>
            <h2 id = "identifiant"> <?= htmlspecialchars($utilisateur->getIdentifiant()) ?></h2>
        </div>
        <?php
        $bool = false;
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $utilisateur->getIdentifiant()) {
            $bool = true;
            $pronom = 'Mes';
            echo '<a href="index.php?action=disconnected&controller=utilisateur">
        <img class="sortie" src="../web/images/logo_sortie.png" alt="Déconnexion"></a>';
        } else {
            $pronom = 'Ses';
        } ?>

    </div>
    <div id = "modif" style="margin-bottom: 60px">
        <?php
        if ($bool) {
            echo '<a class="lien"
       href="index.php?controller=utilisateur&action=update&idUtilisateur=' . rawurlencode($utilisateur->getIdentifiant()) . ' ">
        Modifier les informations</a>';
        } ?>
    </div>
    <h2 class = custom_titre><?= $pronom ?> questions : </h2>
    <ul class=" listes_sans_puces">
        <?php foreach ($questions as $question) {
            echo '<li class = "user_questions"><a href = "index.php?controller=question&action=read&idQuestion=' . rawurlencode($question->getId()) . '">
    ' . $question->getTitre() . '</a></li>';
        }
        ?>
    </ul>

    <h2 class = custom_titre><?= $pronom ?> propositions : </h2>
    <ul class="listes_sans_puces">
        <?php foreach ($propositions as $proposition) {
            echo '<li class = "user_questions"><a href = "index.php?controller=proposition&action=read&idProposition=' . rawurlencode($proposition->getId()) . '">
    ' . $proposition->getTitre() . '</a></li>';
        }
        ?>
    </ul>

<?php
if ($bool) {
    echo '<p  id = "suppression"><a class="link-custom" href="index.php?action=delete&controller=utilisateur&idUtilisateur=' . rawurlencode($utilisateur->getIdentifiant()) . '">Supprimer
    mon compte </a></p>';
    if (ConnexionUtilisateur::estAdministrateur()) {
        echo '<p id = "new"><a href="index.php?action=create&controller=utilisateur">Créer un autre compte </a></p>';
    }
}
