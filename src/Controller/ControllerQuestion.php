<?php

namespace App\Vote\Controller;


use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Section;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\SectionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerQuestion
{
    public static function create()
    {
        if (!isset($_SESSION)) {
            session_start();
            $_SESSION = array();
            session_destroy();
        }

        self::form();
    }

    public static function read()
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $sections = (new SectionRepository())->select($_GET['idQuestion'],'*',"idquestion");
        //$propositions = (new PropositionRepository())->select($_GET['idQuestion'],"*","idQuestion");

        Controller::afficheVue('view.php', ["question" => $question,
                                                "sections" => $sections,
                                                //"propositions" => $propositions,
                                                "pagetitle" => "Detail question",
                                                "cheminVueBody" => "Question/detail.php"]);
    }

    public static function readAll()
    {
        $questions = (new QuestionRepository())->selectAll();

        Controller::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "Question/list.php"]);
    }

    public static function form(): void
    {
        $view = "";
        $step = $_GET['step'] ?? 1;
        $params = array();
        switch ($step) {
            case 1:
                $view = "step-1";
                break;
            case 2:
                $view = "step-2";
                break;
            case 3:
                $view = "step-3";
                break;
            case 4:
                if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                    $row = $_POST['row'];
                    $keyword = $_POST['keyword'];
                    $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
                    $params['utilisateurs'] = $utilisateurs;
                }
                $view = "step-4";
                break;
            case 5:
                if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                    $row = $_POST['row'];
                    $keyword = $_POST['keyword'];
                    $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
                    $params['utilisateurs'] = $utilisateurs;
                }
                $view = "step-5";
                break;
            case 6:
                $view = "step-6";
                break;

        }

        Controller::afficheVue('view.php',
            array_merge(["pagetitle" => "Créer une question",
                "cheminVueBody" => "Question/create/" . $view . ".php"], $params));
    }


    public static function search()
    {
        $utilisateurs = array();
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Question/create/step-4.php"]);
    }

    public static function created(): void
    {
        session_start();
        $calendrier = new Calendrier($_SESSION['debutEcriture'], $_SESSION['finEcriture'], $_SESSION['debutVote'], $_SESSION['finVote']);
        $calendierBD = (new CalendrierRepository())->sauvegarder($calendrier);
        if ($calendierBD != null) {
            $calendrier->setIdCalendrier($calendierBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }

        //var_dump($sections);
        $utilisateur = (new UtilisateurRepository)->select("hambrighta");
        $question = new Question($_SESSION['Titre'], "description", $calendrier, $utilisateur);
        $questionBD = (new QuestionRepository())->sauvegarder($question);
        if ($questionBD != null) {
            $question->setId($questionBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }

        $auteurs = $_SESSION['auteurs'];
        $votants = $_SESSION['votants'];

        $sections = $_SESSION['Sections'];
        foreach ($sections as $value) {
            $section = new Section($value['titre'], $value['description'], $question);
            $sectionBD = (new SectionRepository())->sauvegarder($section);
            if ($sectionBD != null) {
                $section->setId($sectionBD);
            } else {
                Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
            }
        }

        $questions = (new QuestionRepository())->selectAll();

        Controller::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Question crée",
                "cheminVueBody" => "Question/created.php"]);

    }


    public static function recap()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/step-6.php"]);
    }
}