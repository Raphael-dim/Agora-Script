<?php
session_start();
foreach ($_POST as $key => $value) {
    $_SESSION['post'][$key] = $value;
}
require_once "../src/View/Utilisateurs/select.php";
?>
