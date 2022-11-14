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
    <nav>
        <ul id="Menu" style="list-style-type: none">
            <li class="grosmenu" ><a href = "index.php?action=home&controller=accueil">Accueil</a></li>
            <li class="grosmenu" ><a href = "index.php?action=search&controller=utilisateur">Chercher un utilisateur</a></li>
            <li class="grosmenu" ><a href = "index.php?action=create&controller=proposition">Créer une proposition</a></li>
            <li class="grosmenu" ><a href = "index.php?action=readAll&controller=question">Liste des questions</a></li>
            <li class="grosmenu" ><a href = "index.php?action=connexion&controller=utilisateur">Connexion</a></li>
        </ul>
    </nav>
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