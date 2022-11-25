<?php

namespace App\Vote\Controller;

use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;
use App\Vote\Model\Repository\AuteurRepository;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\SectionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerProposition
{

    public static function create()
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
            "cheminVueBody" => "Proposition/create.php",
            "question" => $question]);
    }

    public static function created()
    {
        $question = (new QuestionRepository())->select($_GET["idQuestion"]);
        $responsable = (new ResponsableRepository())->select("hambrighta");
        $proposition = new Proposition($_POST['titre'],$responsable, $question);
        $propositionBD = (new PropositionRepository())->sauvegarder($proposition);
        $proposition->setId($propositionBD);
        $sections = $question->getSections();
        foreach ($sections as $section) {
            $propositionSection = new PropositionSection($proposition, $section, $_POST['contenu'.$section->getId()]);
            (new PropositionSectionRepository())->sauvegarder($propositionSection);

        }

        $questions = (new QuestionRepository())->selectAll();
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
            "cheminVueBody" => "Proposition/created.php",
            "questions" => $questions]);
    }
}
