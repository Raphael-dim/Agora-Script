<?php

require_once '../src/Lib/Psr4AutoloaderClass.php';

use App\Vote\Controller\ControllerAccueil;
use App\Vote\Model\DatabaseConnection as Model;

/*
 * Chargement du namespace
 */
$loader = new App\Vote\Lib\Psr4AutoloaderClass();
$loader->addNamespace('App\Vote', __DIR__ . '/../src');
$loader->register();

$pagetitle = "index";



/*
 * On verifie que le controlleur est défini
 * sinon on redirige vers l'acceuil
 */
if (!isset($_GET["controller"])) {
    $controller = "accueil";
} else {
    $controller = $_GET["controller"];
}

/*
 * On verifie que l'action est définie
 * sinon on redirige vers l'action home du Controlleur Acceuil
 */
if (!isset($_GET["action"])) {
    $action = "home";
} else {
    $action = $_GET["action"];
}


/*
 *  On récupere le controlleur depuis l'url
 */
$controllerClassName = "App\Vote\Controller\Controller" . ucfirst($controller);

/*
 *  Si le controlleur et l'action existe, on lance la fonction associée,
 * sinon on affiche la page d'erreur
 */
if (class_exists($controllerClassName)) {
    if (in_array($action, get_class_methods(new $controllerClassName)) == true) {
        $controllerClassName::$action();
    } else {
        ControllerAccueil::erreur();
    }
} else {
    ControllerAccueil::erreur();
}

