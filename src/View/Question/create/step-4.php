<?php
session_start();
use App\Vote\Config\FormConfig as FormConfig;

$_SESSION['type'] = 'auteurs';

if(array_key_exists('user', $_POST)) {
    adduser($_POST["user"]);
}
if(array_key_exists('delete', $_POST)) {
    removeuser($_POST["delete"]);
}
if (isset($_POST['next'])){
    FormConfig::postSession();
    $_SESSION['step'][3] = 3;
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
}else if (isset($_POST['previous'])){
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=3");
}



function adduser(String $id) : void
{
    if (!in_array($id,$_SESSION[$_SESSION['type']]))
    {
        $_SESSION[$_SESSION['type']][] = $id;
    }
}

function removeuser(String $id) : void
{

    if (($key = array_search($id, $_SESSION[$_SESSION['type']])) !== false){
        unset($_SESSION[$_SESSION['type']][$key]);
    }
}


require_once "../src/View/Utilisateurs/select.php";
?>
<form method="post">
    <input type="submit" name=next value="Suivant"/>
    <input type="submit" name=previous value="Retour" formnovalidate/>
</form>