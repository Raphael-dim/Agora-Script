<?php

namespace App\Vote\Controller;

class ControllerQuestion
{
    public static function create()
    {
        self::afficheVue("Question/create.php");
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}