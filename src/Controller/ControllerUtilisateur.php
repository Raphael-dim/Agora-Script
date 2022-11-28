<?php

namespace App\Vote\Controller;

use App\Vote\Config\FormConfig;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur
{
    public static function readAll()
    {
        $utilisateurs = (new UtilisateurRepository())->selectAll();     //appel au modèle pour gerer la BD
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]); //"redirige" vers la vue
    }

    public static function connexion()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Connexion",
                "cheminVueBody" => "Utilisateurs/connexion.php"]);
    }

    public static function disconnected()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        ControllerAccueil::home();
    }

    public static function connected()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $mdp = $_POST['mdp'];
        $id = $_POST['identifiant'];
        $utilisateur = ((new UtilisateurRepository())->select($id));

        if (!isset($utilisateur)) {
            Controller::afficheVue('view.php', ["pagetitle" => "erreur", "cheminVueBody" => "Accueil/erreur.php"]);
        } else {
            $_SESSION['user'] = array();
            $_SESSION['user']['id'] = $id;
            ControllerAccueil::home();
        }
    }

    public static function read()
    {
        session_start();
        $utilisateur = ((new UtilisateurRepository))->select($_SESSION['user']['id']);
        Controller::afficheVue('view.php', ['pagetitle' => "Profil",
            "cheminVueBody" => "Utilisateurs/detail.php", "Utilisateur" => $utilisateur]);
    }

    public static function create()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Inscription",
                "cheminVueBody" => "Utilisateurs/create.php"]);
    }

    public static function search()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Utilisateurs/search.php"]);
    }

    public static function readKeyword()
    {
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]);
    }

    public static function select()
    {
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/step-4.php"]);
    }


}