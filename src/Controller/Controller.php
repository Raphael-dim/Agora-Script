<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class Controller
{
    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/View/$cheminVue"; // Charge la vue
    }
}