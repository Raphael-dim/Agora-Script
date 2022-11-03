<?php

namespace App\Vote\Controller;


use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Section;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\SectionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

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

        self::afficheVue('view.php', ["question" => $question,
            "sections" => $sections,
            "pagetitle" => "Detail question",
            "cheminVueBody" => "Question/detail.php"]);
    }


    /*
     * Liste les questions
     */

    public static function readAll()
    {
        $questions = (new QuestionRepository())->selectAll();

        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Liste des questions",
                "cheminVueBody" => "Question/list.php"]);
    }

    /*
     * Lancement des page du formulaire de création de la Question
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

        self::afficheVue('view.php',
            array_merge(["pagetitle" => "Créer une question",
                "cheminVueBody" => "Question/create/" . $view . ".php"], $params));
    }


    /*
     * Recherche de Question
     */
    public static function search()
    {
        $utilisateurs = array();
        self::afficheVue('view.php',
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
            self::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        }


        //var_dump($sections);
        $auteur = (new UtilisateurRepository)->select("hambrighta");

        $creation = date("Y/m/d H:i:s");

        $question = new Question($_SESSION['Titre'], $_SESSION['Description'], $creation, $calendrier, $auteur);
        $questionBD = (new QuestionRepository())->sauvegarder($question);
        if ($questionBD != null) {
            $question->setId($questionBD);
        } else {
            self::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
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
                self::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
            }
        }

        $questions = (new QuestionRepository())->selectAll();

        self::afficheVue('view.php',
            ["questions" => $questions,
                "pagetitle" => "Question crée",
                "cheminVueBody" => "Question/created.php"]);

    }

    public static function update(): void
    {
        self::afficheVue('view.php', ["pagetitle" => "Modifier Question", "cheminVueBody" => "question/create/step-1.php", "idQuestion" => $_GET['idQuestion']]);
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
            $diff = count($ancSections) - count($nouvSections) - 1;
            while ($diff > count($nouvSections)) {
                var_dump($ancSections[$diff]);
                (new SectionRepository())->delete($ancSections[$diff]);
                $diff--;
            }
        }


        $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
        self::afficheVue('view.php', ["pagetitle" => "Question modifiée", "cheminVueBody" => "question/updated.php", "questions" => $questions]);
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


    private
    static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $paramètres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}