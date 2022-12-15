<?php
echo '<div class = "custom-form">';
use App\Vote\Config\FormConfig as FormConfig;
use App\Vote\Controller\Controller;
use App\Vote\Lib\MessageFlash;

$_SESSION[FormConfig::$arr]['type'] = 'responsables';

if (array_key_exists('user', $_POST)) {
    adduser($_POST["user"]);
}
if (array_key_exists('delete', $_POST)) {
    removeuser($_POST["delete"]);
}
if (isset($_POST['next'])) {
    FormConfig::postSession();
    $_SESSION[FormConfig::$arr]['step'][3] = 3;
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
} else if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=3");
}


function adduser(string $id): void
{
    if (!in_array($id, $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']])) {
        $_SESSION[FormConfig::$arr][$_SESSION[FormConfig::$arr]['type']][] = $id;
    }
    else{
        MessageFlash::ajouter('warning', "Cet utilisateur est déja selectionné en tant que responsable");
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
    <input type="submit" name=previous value="Retour" id="precedent" formnovalidate>
    <input type="submit" name=next value="Suivant" id="suivant" >
</form>
</div>