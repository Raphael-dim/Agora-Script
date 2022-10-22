<?php
namespace App\Vote\Controller;
use App\Vote\Model\Repository\UtilisateurRepository;
class ControllerUtilisateur
{
    public static function readAll() {
        $utilisateurs = (new UtilisateurRepository())->selectAll();;     //appel au modèle pour gerer la BD
        ControllerUtilisateur::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]); //"redirige" vers la vue
    }

    public static function search(){
        ControllerUtilisateur::afficheVue('view.php',
            ["pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Utilisateurs/search.php"]);
    }

    public static function readKeyword(){
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword,$row);
        ControllerUtilisateur::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]);
    }

    public static function select(){
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword,$row);
        ControllerUtilisateur::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/selectauteurs.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require "../src/view/$cheminVue"; // Charge la vue
    }
}