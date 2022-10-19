<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagetitle; ?></title>
    <link href = "css/global.css" rel = "stylesheet">
    <link href = "css/nav.css" rel = "stylesheet">
</head>
<body>
<header>
    <nav>
        <ul id="Menu" style="list-style-type: none">
            <li class="grosmenu" ><a href = "index.php?action=home&controller=accueil">Accueil</a></li>
            <li class="grosmenu" ><a href = "index.php?action=search&controller=utilisateur">Chercher un utilisateur</a></li>
            <li class="grosmenu" ><a href = "index.php?action=create&controller=question">Creer une question</a></li>
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
        Site de vote de ...
    </p>
</footer>
</body>
</html>