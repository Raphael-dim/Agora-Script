
<?php

use App\Vote\Config\FormConfig as FormConfig;

require "../src/View/Utilisateurs/searchselect.php";
if (!isset($utilisateurs)) {
    $utilisateurs = [];
}

if (isset($_POST["keyword"])) {
    $keyword = $_POST["keyword"];
} else {
    $keyword = "";
}
?>

<link href="css/UserSelect.css" rel="stylesheet">
<form method=post >
    <?php
    foreach ($_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']] as $responsable) {
        echo '
                <span id="utilisateurs" class="listes">
                <button type = submit value = "' . htmlspecialchars($responsable). '" name = "delete">' . htmlspecialchars($responsable) . '</button>
                
                </span>
            ';
    }
    ?>
    <input type="hidden" name="row" value="nom">
    <input type="hidden" name="keyword" value="<?php echo $keyword ?>">
</form>

<div class = "select_box">
    <?php
    foreach ($utilisateurs as $utilisateur) {
        $nom = htmlspecialchars($utilisateur->getNom());
        $prenom = htmlspecialchars($utilisateur->getPrenom());
        $identifiant = $utilisateur->getIdentifiant();
        if (in_array($identifiant, $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']])) {
            $disable = "disabled";
        } else {
            $disable = "";
        }
        echo '
    
                        <form class = "form_select" method= post>
                            <label class = "user_name">' . $nom . ' ' . $prenom . '</label>
                            <button class = "user_select" type = submit value = "' . $identifiant . '" name = "user"></button>
                            
                            <input type ="hidden" name = "row" value = "nom" >
    
                            <input type = "hidden" name = "keyword" value ="' . $keyword . '"> 
           
                            
    
                        </form>
                        ';
    }
    ?>
</div>

