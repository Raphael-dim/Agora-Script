<?php
require "../src/View/Utilisateurs/searchselect.php";
if (!isset($utilisateurs)){
    $utilisateurs = [];
}

if (isset($_POST["keyword"])){
    $keyword = $_POST["keyword"];
}
else{
    $keyword = "";
}

if(array_key_exists('user', $_POST)) {
    adduser($_POST["user"]);
}
if(array_key_exists('delete', $_POST)) {
    removeuser($_POST["delete"]);
}

function adduser(String $id) : void
{
    if (!in_array($id,$_SESSION['auteurs']))
    {
        $_SESSION['auteurs'][] = $id;
    }
}

function removeuser(String $id) : void
{

    if (($key = array_search($id, $_SESSION['auteurs'])) !== false){
        unset($_SESSION['auteurs'][$key]);
    }
}
var_dump( $_SESSION['post']);
?>


<style>
    span{
        background:lightblue;
        opacity: 0.8;
        padding:1px;
        border:dodgerblue;
    }
</style>

<form method = post>
    <?php
        foreach ($_SESSION['auteurs'] as $auteur){
            echo '

                <span><button type = submit value = "'.$auteur.'" name = "delete">'.$auteur.'</button></span>

            ';

        }
    ?>
<input type ="hidden" name = "row" value = "nom" />
<input type = "hidden" name = "keyword" value ="<?php echo $keyword ?>"/>
</form>
<?php
        foreach ($utilisateurs as $utilisateur) {
            $nom =   htmlspecialchars($utilisateur->getNom());
            $prenom =   htmlspecialchars($utilisateur->getPrenom());
            $identifiant = $utilisateur->getIdentifiant();
            if (in_array($identifiant,$_SESSION['auteurs'])){
                $disable = "disabled";
            }
            else{
                $disable = "";
            }
            echo '

                    <form method= post>
                        <p><button type = submit value = "'.$identifiant.'" name = "user">' . $nom .' ' . $prenom . '</button></p>
                        <input type ="hidden" name = "row" value = "nom" />
                        <input type = "hidden" name = "keyword" value ="'. $keyword . '"/> 
                        
                    </form>
                    '
            ;
        }
    ?>
    <input type="submit" value="Selectionner" name ="path">

