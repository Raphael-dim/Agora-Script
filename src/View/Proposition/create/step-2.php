<?php

use App\Vote\Config\FormConfig as FormConfig;
$_SESSION[FormConfig::$arr]['type'] = "co-auteur";

if (isset($_POST['next'])) {

    if ($_GET['action'] == "create") {
        FormConfig::postSession();
        FormConfig::redirect('index.php?controller=proposition&action=created&idQuestion=' . $_POST['idQuestion']);
    } else if($_GET['action'] == "update"){
        FormConfig::postSession();
        FormConfig::redirect("index.php?controller=proposition&action=updated&idProposition=" . $_GET['idProposition']);
    }
}else if(isset($_POST['previous'])){
    if ($_GET['action'] == "create") {
        FormConfig::postSession();
        FormConfig::redirect('index.php?controller=proposition&action=create&step=2&idQuestion=' . $_POST['idQuestion']);
    } else if($_GET['action'] == "update"){
        FormConfig::postSession();
        FormConfig::redirect("index.php?controller=proposition&action=update&step=2&idProposition=" . $_GET['idProposition']);
    }
}

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
<form method="post" class="nav">
    <input type="submit" name=previous value="Retour"/>
    <input type="submit" name=next value="Valider"/>
</form>