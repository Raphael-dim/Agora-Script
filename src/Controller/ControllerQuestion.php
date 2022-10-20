<?php

namespace App\Vote\Controller;

use Question;

class ControllerQuestion
{
    public static function create()
    {
        self::afficheVue('view.php',
            ["pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/create.php"]);
    }

    public static function create2()
    {
        self::afficheVue('view.php',
            ["pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/create2.php"]);
    }

    public static function created(): void
    {
        $question = new Question($_GET['titre'], $_GET['nbSections'], $_GET['sections']);
        $cree = QuestionRepositoru::sauvegarder($voiture);
        $voitures = (new VoitureRepository())->selectAll(); //appel au modèle pour gerer la BD
        if ($cree) {
            self::afficheVue('view.php', ["pagetitle" => "Voiture crée", "cheminVueBody" => "voiture/created.php", "voitures" => $voitures]);
        } else {
            self::error("Voiture déjà crée");
        }
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}