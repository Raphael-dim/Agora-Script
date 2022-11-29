<?php

use App\Vote\Config\FormConfig as FormConfig;
$_SESSION[FormConfig::$arr]['type'] = "co-auteur";
//$_SESSION[FormConfig::$arr]['co-auteur'] = array();
//var_dump($_SESSION[FormConfig::$arr]);
$idquestion = $_GET['idQuestion'];
var_dump($idquestion);

if (array_key_exists('user', $_POST)) {
    adduser($_POST["user"]);
}
if (array_key_exists('delete', $_POST)) {
    removeuser($_POST["delete"]);
}
FormConfig::postSession();

function adduser(string $id): void
{
    if (!in_array($id, $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']])) {
        $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']][] = $id;
    }
}

function removeuser(string $id): void
{
    if (($key = array_search($id, $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']])) !== false) {
        unset($_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']][$key]);
    }
}


require_once "../src/View/Utilisateurs/select.php";
?>
<form method="post" class="nav" action=index.php?action=created&controller=coauteur&idQuestion=<?php echo $idquestion?>>
    <input type="submit" name=next value="Valider"/>
</form>