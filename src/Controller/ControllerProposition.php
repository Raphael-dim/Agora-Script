<?php

namespace App\Vote\Controller;

use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerProposition
{

    public static function create()
    {
        $questions = (new QuestionRepository())->selectAll();
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                                                "cheminVueBody" => "Proposition/create.php",
                                                "questions" => $questions]);
    }

    public static function created()
    {
        $proposition = new Proposition($_POST['Titre'],$_POST['contenu'],(new UtilisateurRepository)->select("hambrighta"),(new QuestionRepository())->select($_POST['question']));
        (new PropositionRepository())->sauvegarder($proposition);
        $questions = (new QuestionRepository())->selectAll();
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                                                "cheminVueBody" => "Proposition/created.php",
                                                "questions" => $questions]);
    }
}
