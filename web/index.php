<?php

require_once '../src/Lib/Psr4AutoloaderClass.php';

$loader = new App\Vote\Lib\Psr4AutoloaderClass();
$loader->addNamespace('App\Vote', __DIR__ . '/../src');
// register the autoloader
$loader->register();

$pagetitle = "index";

\App\Vote\Controller\ControllerUtilisateur::readAll();

//if (isset($_GET['controller'])) {
//    $controller = $_GET['controller'];
//} else {
//    $controller = "utilisateur";
//}
//$controllerClassName = 'App\Vote\Controller\Controller' . ucfirst($controller);
//
//if (isset($_GET['action'])) {
//    $action = $_GET['action'];
//} else {
//    $action = "readAll";
//}
//if (class_exists($controllerClassName) && in_array($action, get_class_methods($controllerClassName))) {
//    $controllerClassName::$action();
//}



?>