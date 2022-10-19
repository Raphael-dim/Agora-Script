<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerAccueil
{

    public static function home() {
        ControllerAccueil::afficheVue('view.php',
            ["pagetitle" => "Accueil",
                "cheminVueBody" => "Accueil/accueil.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}