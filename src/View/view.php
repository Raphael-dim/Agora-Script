<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagetitle; ?></title>
    <link href = "css/global.css" rel = "stylesheet">
    <link href = "css/nav.css" rel = "stylesheet">
    <link rel="icon" type="image/x-icon" href="images/logo_vote.ico" />
</head>
<body>
<header>
        <?php
        require __DIR__ . "/nav.php";
        ?>
</header>
<main>
    <?php
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        Site de vote des Prog'Raleur
    </p>
</footer>
</body>
</html>