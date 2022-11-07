<?php

namespace App\Vote\Controller;


use App\Vote\Model\DatabaseConnection as DatabaseConnection;
use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Section;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\Repository\AuteurRepository;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\PropositionRepository;
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

        $sections = $question->getSections();
        $auteurs = $question->getResponsables();
        $votants = $question->getVotants();
        $propositions = (new PropositionRepository())->select($_GET['idQuestion']);
        self::afficheVue('view.php', ["question" => $question,
            "sections" => $sections,
            "auteurs" => $auteurs,
            "votants" => $votants,
            "propositions" => $propositions,
            "pagetitle" => "Detail question",
            "cheminVueBody" => "Question/detail.php"]);
    }


    /*
     * Liste les questions
     */

    public static function readAll()
    {
        //A optimiser
        $questions = (new QuestionRepository())->selectAll();

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
        session_start();
        $calendrier = new Calendrier($_SESSION['debutEcriture'], $_SESSION['finEcriture'], $_SESSION['debutVote'], $_SESSION['finVote']);
        $calendierBD = (new CalendrierRepository())->sauvegarder($calendrier);
        if ($calendierBD != null) {
            $calendrier->setIdCalendrier($calendierBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }


        //var_dump($sections);
        $organisateur = (new UtilisateurRepository)->select("hambrighta");

        $creation = date("Y/m/d H:i:s");

        $question = new Question($_SESSION['Titre'], $_SESSION['Description'], $creation, $calendrier, $organisateur);
        $questionBD = (new QuestionRepository())->sauvegarder($question);
        if ($questionBD != null) {
            $question->setId($questionBD);
        } else {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }


        $responsables = $_SESSION['responsables'];

        foreach ($responsables as $responsable) {
            $sql = "INSERT INTO Responsables (idQuestion,idUtilisateur)";
            $sql = $sql . " VALUES (" . $question->getId() . ", '" . $responsable . "');";
            $sql = substr($sql, 0, -1);

            // Préparation de la requête
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

            // On donne les valeurs et on exécute la requête
            try {
                $pdoStatement->execute();
            } catch (PDOException $e) {
                echo($e->getMessage());
            }
        }

            /*
                foreach ($responsables as $responsable) {
                $utilisateur = (new UtilisateurRepository())->select($responsable);
                $responsableBD = (new ResponsableRepository())->sauvegarder($utilisateur);
            }*/

        $votants = $_SESSION['votants'];
        foreach($votants as $votant){

            $sql = "INSERT INTO Votants (idQuestion,idUtilisateur)";
            $sql = $sql . " VALUES (" . $question->getId() . ", '" . $votant . "');";
            $sql = substr($sql, 0, -1);

            // Préparation de la requête
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

            // On donne les valeurs et on exécute la requête
            try {
                $pdoStatement->execute();
            } catch (PDOException $e) {
                echo($e->getMessage());
            }
        }

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
        session_unset();

    }

    public static function update(): void
    {
        self::afficheVue('view.php', ["pagetitle" => "Modifier une question",
            "cheminVueBody" => "question/create/step-1.php",
            "idQuestion" => $_GET['idQuestion']]);
    }

    public static function updated(): void
    {
        session_start();
        $question = (new QuestionRepository())->select($_SESSION['idQuestion']);
        $question->setTitre($_SESSION['Titre']);
        $question->setDescription($_SESSION['Description']);
        (new QuestionRepository())->update($question);


        $calendrier = (new CalendrierRepository())->select($question->getCalendrier()->getIdCalendrier());
        $calendrier->setDebutEcriture($_SESSION['debutEcriture']);
        $calendrier->setFinEcriture($_SESSION['finEcriture']);
        $calendrier->setDebutVote($_SESSION['debutVote']);
        $calendrier->setFinVote($_SESSION['finVote']);
        (new CalendrierRepository())->update($calendrier);


        $ancSections = $question->getSections();
        $nouvSections = $_SESSION['Sections'];
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


        $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
        self::afficheVue('view.php', ["pagetitle" => "Question modifiée",
            "cheminVueBody" => "question/updated.php",
            "questions" => $questions]);
        session_unset();
    }

    public static function delete(): void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        foreach ($question->getSections() as $section) {
            (new SectionRepository())->delete($section->getId());
        }
        (new QuestionRepository())->delete($_GET['idQuestion']);
        (new CalendrierRepository())->delete($question->getCalendrier()->getIdCalendrier());
        $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
        self::afficheVue('view.php', ["pagetitle" => "Question supprimée", "cheminVueBody" => "question/deleted.php", "questions" => $questions]);
    }


private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $paramètres
        require "../src/view/$cheminVue"; // Charge la vue
    }
    
}