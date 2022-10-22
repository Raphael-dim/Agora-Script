<?php

namespace App\Vote\Controller;


use App\Vote\Model\Repository\UtilisateurRepository;

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

    public static function search(){
        $utilisateurs =array();
        ControllerQuestion::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Question/create/selectauteurs.php"]);
    }

    public static function selectAuteurs(){
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword,$row);
        self::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,"pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/selectauteurs.php"]);
    }

    public static function selectVotants(){
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword,$row);
        self::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,"pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/selectvotants.php"]);
    }

    public static function recap(){
        self::afficheVue('view.php',
            ["pagetitle" => "Creer une question",
                "cheminVueBody" => "Question/create/recap.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}