<?php

require_once "src/Model/Model.php";
$pagetitle = "index";
$cheminVueBody = "Acceuil/acceuil.php";
if (Model::getPDO()!= NULL){
    echo "<p>connexion à la base de donnée confirmée</p>";
}
require "src/View/view.php";
?>