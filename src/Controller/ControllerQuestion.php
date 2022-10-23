<?php

namespace App\Vote\Controller;


use App\Vote\Model\DataObject\Question;
use App\Vote\Model\Repository\QuestionRepository;
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


    public static function search()
    {
        $utilisateurs = array();
        self::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Question/create/step-4.php"]);
    }

    public static function created(): void
    {
        $question = new Question($_GET['id'], $_GET['titre'], $_GET['nbSections']);
        $cree = (new QuestionRepository())->sauvegarder($question);
        $questions = (new QuestionRepository())->selectAll(); //appel au modèle pour gerer la BD
        if ($cree) {
            self::afficheVue('view.php', ["pagetitle" => "Question crée", "cheminVueBody" => "Question/created.php", "questions" => $questions]);
        } else {
            // ERREUR À FAIRE
        }
    }


    public static function recap()
    {
        self::afficheVue('view.php',
            ["pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/step-6.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $paramètres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}