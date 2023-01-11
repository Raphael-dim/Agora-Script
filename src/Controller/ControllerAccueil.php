<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerAccueil
{
    /**
     * Cette fonction affiche la page d'accueil
     */
    public static function home()
    {
        // Utilisation de la méthode statique 'afficheVue' de la classe 'Controller' pour afficher la vue avec les paramètres spécifiés
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil", "cheminVueBody" => "Accueil/accueil.php"]);
    }

    /**
     * Cette fonction affiche la page d'erreur
     */
    public static function erreur()
    {
        // Utilisation de la méthode statique 'afficheVue' de la classe 'Controller' pour afficher la vue avec les paramètres spécifiés
        Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
            "cheminVueBody" => "Accueil/erreur.php"]);
    }
}