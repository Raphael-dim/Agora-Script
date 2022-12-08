<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerAccueil
{
    public static function home()
    {
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil", "cheminVueBody" => "Accueil/accueil.php"]);
    }

    public static function erreur()
    {
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
            "cheminVueBody" => "Accueil/erreur.php"]);
    }
}