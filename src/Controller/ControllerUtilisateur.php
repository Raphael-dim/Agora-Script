<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur
{
    public static function readAll()
    {
        $utilisateurs = (new UtilisateurRepository())->selectAll();;     //appel au modèle pour gerer la BD
        ControllerUtilisateur::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des utilisateurs",
                "cheminVueBody" => "Utilisateur/list.php"]); //"redirige" vers la vue
    }

    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__. "/../View/$cheminVue"; // Charge la vue
    }
}