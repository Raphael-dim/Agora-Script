<?php
session_start();
$_SESSION['type'] = 'votants';
$_SESSION['next'] = "index.php?action=recap&controller=question";
$_SESSION['current'] = "index.php?action=selectVotants&controller=question";
foreach ($_POST as $key => $value) {
    $_SESSION['post'][$key] = $value;
}
require_once "../src/View/Utilisateurs/select.php";
?>
