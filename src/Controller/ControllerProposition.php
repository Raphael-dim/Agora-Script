<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\HTTP\Session;
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
        if (!isset($_GET['idQuestion'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() ||
            !Responsable::estResponsable($question, $user['id'])) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition, 
            vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition tant 
            que la phase d'écriture n'a pas débuté.");
            $bool = false;
        }
        if (Responsable::aCreeProposition($question, $user['id'])) {
            MessageFlash::ajouter("warning", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                "cheminVueBody" => "Proposition/create.php",
                "question" => $question]);
        }
    }

    public static function read()
    {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $sections = $question->getSections();
        $idProposition = $_GET['idProposition'];
        Controller::afficheVue('view.php', [
            "question" => $question,
            "idProposition" => $idProposition,
            "proposition" => $proposition,
            "sections" => $sections,
            "pagetitle" => "Detail proposition",
            "cheminVueBody" => "Proposition/detail.php"]);
    }

    public static function readAll()
    {
        if (!isset($_GET['idQuestion'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $propositions = (new PropositionRepository())->selectWhere($_GET['idQuestion'], '*', 'idquestion');
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $votants = $question->getVotants();
        Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
            "votants" => $votants,
            "cheminVueBody" => "Proposition/list.php",
            "propositions" => $propositions, "question" => $question]);
    }


    public static function created()
    {
        if (!isset($_GET['idQuestion'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $question = (new QuestionRepository())->select($_GET["idQuestion"]);
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() || !Responsable::estResponsable($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition, 
            vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition tant 
            que la phase d'écriture n'a pas débuté.");
            $bool = false;
        }
        if (Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        }
        $responsable = (new ResponsableRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $proposition = new Proposition($_POST['titre'], $responsable, $question, 0);
        $propositionBD = (new PropositionRepository())->sauvegarder($proposition);
        $proposition->setId($propositionBD);
        $sections = $question->getSections();
        foreach ($sections as $section) {
            $propositionSection = new PropositionSection($proposition, $section, $_POST['contenu' . $section->getId()]);
            (new PropositionSectionRepository())->sauvegarder($propositionSection);

        }

        MessageFlash::ajouter("success", "La proposition a bien été crée.");
        Controller::redirect("index.php?controller=question&action=readAll");
    }
}
