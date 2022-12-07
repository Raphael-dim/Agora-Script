<?php

namespace App\Vote\Controller;


use App\Vote\Config\FormConfig;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DatabaseConnection as DatabaseConnection;
use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Section;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\AuteurRepository;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\SectionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;
use App\Vote\Model\Repository\VotantRepository;

class ControllerQuestion
{

    /*
     * Réinitialise les variables de session et
     * lance le formulaire de création
     */
    public static function create()
    {
        if (isset(Session::getInstance()->lire('user')['id'])) {
            FormConfig::setArr('SessionQuestion');
            FormConfig::startSession();
            self::form();
        } else {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer une question si vous n'êtes pas connecté.");
            Controller::redirect("index.php?action=connexion&controller=utilisateur");
        }
    }

    public static function read()
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $propositions = $question->getPropositions();
        $sections = $question->getSections();
        $responsables = $question->getResponsables();
        $votants = $question->getVotants();
        self::afficheVue('view.php', ["question" => $question,
            "sections" => $sections,
            "responsables" => $responsables,
            "propositions" => $propositions,
            "votants" => $votants,
            "pagetitle" => "Detail question",
            "cheminVueBody" => "Question/detail.php"]);
    }


    /*
     * Liste les questions
     */

    public static function readAll()
    {
        //A optimiser
        if (!isset($_GET["selection"])) {
            $_GET["selection"] = "toutes";
        }

        if ($_GET["selection"] == "vote") {
            $questions = (new QuestionRepository())->getPhaseVote();
        } else if ($_GET["selection"] == "ecriture") {
            $questions = (new QuestionRepository())->getPhaseEcriture();
        } else if ($_GET["selection"] == "terminees") {
            $questions = (new QuestionRepository())->getTerminees();
        } else {
            $questions = (new QuestionRepository())->selectAll();
        }

        Controller::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "Question/list.php"]);
    }

    /*
     * Lancement des pages du formulaire de création de la Question
     */
    public static function form(): void
    {
        Session::getInstance();
        FormConfig::setArr('SessionQuestion');
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


    /*
     * Recherche de Question
     */
    public static function search()
    {
        $utilisateurs = array();
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Question/create/step-4.php"]);
    }

    /*
     * Enregistre dans la base de donnée toutes les données relatives à la Question:
     * - Calendrier
     * - Auteurs
     * - Sections
     * - Votants
     */
    public static function created(): void
    {
        $user = Session::getInstance()->lire('user');
        if (is_null($user)) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer une question si vous n'êtes pas connecté.");
            Controller::redirect("index.php?action=readAll&controller=question");
        }
        FormConfig::setArr('SessionQuestion');
        $calendrier = new Calendrier($_SESSION[FormConfig::$arr]['debutEcriture'], $_SESSION[FormConfig::$arr]['finEcriture'], $_SESSION[FormConfig::$arr]['debutVote'], $_SESSION[FormConfig::$arr]['finVote']);
        $calendierBD = (new CalendrierRepository())->sauvegarder($calendrier);
        if ($calendierBD != null) {
            $calendrier->setId($calendierBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }


        //var_dump($sections);
        $organisateur = (new UtilisateurRepository)->select($_SESSION['user']['id']);

        $creation = date("Y/m/d H:i:s");

        $question = new Question($_SESSION[FormConfig::$arr]['Titre'], $_SESSION[FormConfig::$arr]['Description'], $creation, $calendrier, $organisateur);
        $questionBD = (new QuestionRepository())->sauvegarder($question);
        if ($questionBD != null) {
            $question->setId($questionBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }


        $responsables = $_SESSION[FormConfig::$arr]['responsables'];

        foreach ($responsables as $responsable) {
            $utilisateur = new Responsable($question);
            $utilisateur->setIdentifiant($responsable);
            $responsableBD = (new ResponsableRepository())->sauvegarder($utilisateur);
        }

        $votants = $_SESSION[FormConfig::$arr]['votants'];

        foreach ($votants as $votant) {
            $utilisateur = new Votant($question);
            $utilisateur->setIdentifiant($votant);
            $votantBD = (new VotantRepository())->sauvegarder($utilisateur);
        }

        $sections = $_SESSION[FormConfig::$arr]['Sections'];
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

        MessageFlash::ajouter('success', 'La question a bien été crée');
        Controller::redirect("index.php?controller=question&action=readAll");
        FormConfig::startSession();
    }


    public static function updated(): void
    {
        $user = Session::getInstance()->lire('user');
        $question = (new QuestionRepository())->select($_SESSION[FormConfig::$arr]['idQuestion']);
        if (is_null($user) || $user['id'] != $question->getOrganisateur()->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une question dont vous n'êtes par l'organisateur.");
            Controller::redirect("index.php?action=readAll&controller=question");
        }
        FormConfig::setArr('SessionQuestion');
        $question->setTitre($_SESSION[FormConfig::$arr]['Titre']);
        $question->setDescription($_SESSION[FormConfig::$arr]['Description']);
        (new QuestionRepository())->update($question);


        $calendrier = (new CalendrierRepository())->select($question->getCalendrier()->getId());
        $calendrier->setDebutEcriture($_SESSION[FormConfig::$arr]['debutEcriture']);
        $calendrier->setFinEcriture($_SESSION[FormConfig::$arr]['finEcriture']);
        $calendrier->setDebutVote($_SESSION[FormConfig::$arr]['debutVote']);
        $calendrier->setFinVote($_SESSION[FormConfig::$arr]['finVote']);
        (new CalendrierRepository())->update($calendrier);


        $ancSections = $question->getSections();
        $nouvSections = $_SESSION[FormConfig::$arr]['Sections'];
        for ($i = 0; $i < count($nouvSections); $i++) {
            if (count($ancSections) <= $i) {
                $section = new Section($nouvSections[$i]['titre'], $nouvSections[$i]['description'], $question);
                $sectionBD = (new SectionRepository())->sauvegarder($section);
                if ($sectionBD != null) {
                    $section->setId($sectionBD);
                } else {
                    self::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
                }
            } else {
                $ancSections[$i]->setTitre($nouvSections[$i]['titre']);
                $ancSections[$i]->setDescription($nouvSections[$i]['description']);
                (new SectionRepository())->update($ancSections[$i]);
            }
        }
        if (count($ancSections) > count($nouvSections)) {
            for ($diff = count($ancSections) - count($nouvSections); $diff > 0; $diff--) {
                (new SectionRepository())->delete($ancSections[count($ancSections) - 1]->getId());
                unset($ancSections[count($ancSections) - 1]);
            }
        }

        $responsables = $question->getResponsables();
        $ancResponsables = array();
        foreach ($responsables as $responsable) {
            $ancResponsables[] = $responsable->getIdentifiant();
        }
        $tab = array();
        $tab = $_SESSION[FormConfig::$arr]['responsables'];
        $nouvResponsables = array();
        foreach ($tab as $val) {
            $nouvResponsables[] = $val;
        }
        for ($i = 0; $i < sizeof($nouvResponsables); $i++) {
            if (!in_array($nouvResponsables[$i], $ancResponsables)) {
                $utilisateur = new Responsable($question);
                $utilisateur->setIdentifiant($nouvResponsables[$i]);
                $responsableBD = (new ResponsableRepository())->sauvegarder($utilisateur);
            }
            if ($i > count($ancResponsables) && !in_array($ancResponsables[$i], $nouvResponsables)) {
                (new ResponsableRepository())->delete($ancResponsables[$i]);
            }
        }

        $votants = $question->getVotants();
        $ancVotants = array();
        foreach ($votants as $val) {
            $ancVotants[] = $val->getIdentifiant();
        }
        $tab2 = $_SESSION[FormConfig::$arr]['votants'];
        $nouvVotants = array();
        foreach ($tab2 as $val) {
            $nouvVotants[] = $val;
        }
        for ($i = 0; $i < sizeof($nouvVotants); $i++) {
            if (!in_array($nouvVotants[$i], $ancVotants)) {
                $utilisateur = new Votant($question);
                $utilisateur->setIdentifiant($nouvVotants[$i]);
                $votantBD = (new VotantRepository())->sauvegarder($utilisateur);
            }
            if ($i > count($ancVotants) && !in_array($ancVotants[$i], $nouvVotants)) {
                (new VotantRepository())->delete($ancVotants[$i]);
            }
        }


        $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
        MessageFlash::ajouter('success', 'La question a bien été modifiée');
        Controller::redirect("index.php?controller=question&action=readAll");

        FormConfig::startSession();
    }

    public static function update(): void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $user = Session::getInstance()->lire('user');
        if (is_null($user) || $user['id'] != $question->getOrganisateur()->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une question dont vous n'êtes par l'organisateur.");
            Controller::redirect("index.php?action=readAll&controller=question");
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $question->getOrganisateur()->getIdentifiant()) {
            ControllerAccueil::erreur();
        } else {
            FormConfig::setArr('SessionQuestion');
            FormConfig::startSession();
            self::afficheVue('view.php', ["pagetitle" => "Modifier une question",
                "cheminVueBody" => "question/create/step-1.php",
                "idQuestion" => $_GET['idQuestion']]);
        }
    }


    public static function delete(): void
    {
        $user = Session::getInstance()->lire('user');
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        if (is_null($user) || $user['id'] != $question->getOrganisateur()->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas supprimer une question dont vous n'êtes par l'organisateur.");
            Controller::redirect("index.php?action=readAll&controller=question");
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $question->getOrganisateur()->getIdentifiant()) {
            ControllerAccueil::erreur();
        } else if (!isset($_POST["cancel"]) && !isset($_POST["confirm"])) {
            self::afficheVue('view.php', ["pagetitle" => "Question modifiée",
                "cheminVueBody" => "confirm.php",
                "message" => "Êtes vous sûr de vouloir supprimer cette question?",
                "id" => $_GET['idQuestion']]);
        } else if (isset($_POST["cancel"])) {
            self::readAll();
        } else if (isset($_POST["confirm"])) {
            (new QuestionRepository())->delete($_GET['idQuestion']);
            $calendrier = $question->getCalendrier();
            (new CalendrierRepository())->delete($calendrier->getId());
            $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
            MessageFlash::ajouter('success', 'La question a bien été supprimée');
            Controller::redirect("index.php?controller=question&action=readAll");        }
    }

    public static function readKeyword(): void
    {
        $keyword = $_POST['keyword'];
        $questions = (new QuestionRepository())->selectKeyword($keyword, 'titre');
        Controller::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "Question/list.php"]);
    }


    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $paramètres
        require "../src/view/$cheminVue"; // Charge la vue
    }

}