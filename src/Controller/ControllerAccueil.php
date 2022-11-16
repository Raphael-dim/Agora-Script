<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerAccueil
{

    public static function home()
    {
        require "../src/View/Accueil/accueil.php";

    }

    public static function erreur()
    {
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                "cheminVueBody" => "Accueil/erreur.php"]);
    }

}