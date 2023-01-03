<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php

        echo $pagetitle; ?></title>
    <link href="css/global.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
    <link href="css/keyframes.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="images/logo_vote.ico">

    /* Editeur Markdown */
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    /* Parseur Markdown */
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</head>
<body>
<header>
    <?php
    require __DIR__ . "/nav.php";
    ?>
</header>
<?php
if ($pagetitle == 'Detail question') {
    echo '<main class = "pageDétail">';
} else {
    echo '<main>';
}

use App\Vote\Lib\MessageFlash;
use App\Vote\Model\HTTP\Session;


//echo '</div>';
require __DIR__ . "/{$cheminVueBody}";
echo '</main>';
?>

<footer>
    <ul class="footer">
        <li>
            <a href="index.php?controller=accueil&action=credits">Crédits</a>
        </li>
        <li>
            <a>Nous contacter</a>
        </li>
    </ul>
</footer>
<?php

echo '<div class = "pileflash" >';
if (!Session::getInstance()->contient('_messagesFlash')) {
    new MessageFlash();
}
foreach (MessageFlash::lireTousMessages() as $cle => $messagess) {
    $messages = MessageFlash::lireMessages($cle);
    foreach ($messages as $message) {
        echo '<div class="alert alert-' . $cle . '">' . $message . '</div>';
    }
}
echo '</div>'
?>
</body>
</html>

