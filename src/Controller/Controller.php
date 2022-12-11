<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class Controller
{
    /**
     * affiche la vue passée en paramètres avec les variables dans $parametres
     * @param string $cheminVue
     * @param array $parametres
     * @return void
     */
    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/View/$cheminVue"; // Charge la vue
    }

    public static function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}