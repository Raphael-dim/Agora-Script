<?php

require_once '../src/Lib/Psr4AutoloaderClass.php';
use App\Vote\Model\DatabaseConnection as Model;

$loader = new App\Vote\Lib\Psr4AutoloaderClass();
$loader->addNamespace('App\Vote', __DIR__ .  '/../src');
// register the autoloader
$loader->register();

$pagetitle = "index";

if (!isset($_GET["controller"])){
    $controller = "accueil";
}
else{
    $controller = $_GET["controller"];
}

if (!isset($_GET["action"])){
    $action = "home";
}
else{
    $action = $_GET["action"];
}

$controllerClassName ="App\Vote\Controller\Controller".ucfirst($controller);
if (class_exists($controllerClassName)){
    if (in_array($action,get_class_methods(new $controllerClassName))== true){
        $controllerClassName::$action();
    }
    else{

    }
}
else{

}



?>

