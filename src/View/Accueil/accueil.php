<link href="css/accueil.css" rel="stylesheet">
<h1>Bienvenue sur <strong class = "color-blue">Agora Script </strong> !</h1>
    <div id="acceuil_search_form">
        <h1><span>Trouve les réponses à tes questions</span></h1>
        <img id="imgsearch" src="../web/images/background.bmp" alt="background">
        <form id = "form_search"  method="post" action="index.php?controller=question&action=readKeyword">
            <p>
                <input id = "keyword_input" type="text" placeholder="Saisissez un mot clef" name="keyword">
                <input type="image" alt = "Submit" src="../web/images/search.png" class = "search">
            </p>
        </form>
    </div>