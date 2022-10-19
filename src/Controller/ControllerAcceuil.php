<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerAcceuil
{

    public static function home() {
        ControllerAcceuil::afficheVue('view.php',
            ["pagetitle" => "Acceuil",
                "cheminVueBody" => "Acceuil/acceuil.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}