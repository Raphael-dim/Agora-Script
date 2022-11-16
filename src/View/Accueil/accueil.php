
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link href = "css/global.css" rel = "stylesheet">
    <link href = "css/nav.css" rel = "stylesheet">
    <link rel="icon" type="image/x-icon" href="images/logo_vote.ico" />
</head>
<body>
<header>
    <nav>
        <?php
        require "../src/View/nav.php";
        ?>
    </nav>
</header>
<main>
    <p>Page d'accueil du site de vote</p>
    <div id="acceuil_search_form">
        <h1><span>Recherche</span></h1>
        <img id = "imgsearch" src="../web/images/background.bmp"/>
        <form id = "form_search">
            <input id = "keyword_input" type="text" placeholder="Saisissez un mot clef" name="keyword" id="motclef"/>
            <input type="image" alt = "Submit" src="../web/images/search.png" value="Envoyer" class = "search"/>
        </form>
    </div>
</main>
<footer>
    <p>
        Site de vote des Prog'Raleur
    </p>
</footer>
</body>
</html>