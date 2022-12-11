     <h1>Bienvenue sur <strong id = "color-blue">Agora Script </strong> !</h1>
    <div id="acceuil_search_form">
        <h1><span>Trouve les réponses à tes questions</span></h1>
        <img id="imgsearch" src="../web/images/background.bmp" alt="background">
        <form id = "form_search"  method="post" action="index.php?controller=question&action=readKeyword">
            <input id = "keyword_input" type="text" placeholder="Saisissez un mot clef" name="keyword">
            <input type="image" alt = "Submit" src="../web/images/search.png" class = "search">
        </form>
    </div>
