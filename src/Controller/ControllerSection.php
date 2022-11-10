<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\SectionRepository;

class ControllerSection
{

    public static function delete(): void
    {
        (new SectionRepository())->delete($_GET['idSection']);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }

}
