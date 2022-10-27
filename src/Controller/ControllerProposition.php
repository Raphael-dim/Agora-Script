<?php

namespace App\Vote\Controller;

use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerProposition
{

    public static function create()
    {
        $questions = (new QuestionRepository())->selectAll();
        self::afficheVue('view.php', ["pagetitle" => "Accueil",
                                                "cheminVueBody" => "Proposition/create.php",
                                                "questions" => $questions]);
    }

    public static function created()
    {
        //$proposition = new Calendrier($_SESSION['debutEcriture'], $_SESSION['finEcriture'], $_SESSION['debutVote'], $_SESSION['finVote']);
        //(new PropositionRepository())->sauvegarder($proposition);
        $questions = (new QuestionRepository())->selectAll();
        $utilisateur = (new UtilisateurRepository)->select("hambrighta");
        self::afficheVue('view.php', ["pagetitle" => "Accueil",
                                                "cheminVueBody" => "Proposition/created.php",
                                                "questions" => $questions]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}
