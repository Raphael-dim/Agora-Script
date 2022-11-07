<?php
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

<form method=post>
    <?php
    foreach ($_SESSION[$_SESSION['type']] as $responsable) {
        echo '

                <span><button type = submit value = "' . $responsable . '" name = "delete">' . $responsable . '</button></span>

            ';

    }
    ?>
    <input type="hidden" name="row" value="nom"/>
    <input type="hidden" name="keyword" value="<?php echo $keyword ?>"/>
</form>
<?php
foreach ($utilisateurs as $utilisateur) {
    $nom = htmlspecialchars($utilisateur->getNom());
    $prenom = htmlspecialchars($utilisateur->getPrenom());
    $identifiant = $utilisateur->getIdentifiant();
    if (in_array($identifiant, $_SESSION['responsables'])) {
        $disable = "disabled";
    } else {
        $disable = "";
    }
    echo '

                    <form method= post>
                        <p><button type = submit value = "' . $identifiant . '" name = "user">' . $nom . ' ' . $prenom . '</button></p>
                        <input type ="hidden" name = "row" value = "nom" />

                        <input type = "hidden" name = "keyword" value ="' . $keyword . '"/> 
       
                        

                    </form>
                    ';
}
?>


