<?php

namespace App\Vote\Lib;

use App\Vote\Model\HTTP\Session;

class ConnexionUtilisateur
{
// L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        Session::getInstance()->enregistrer('user', array('id' => $loginUtilisateur));
    }

    public static function estConnecte(): bool
    {
        return isset(Session::getInstance()->lire('user')['id']);
    }// À compléter


    public static function deconnecter(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        return Session::getInstance()->lire('user')['id'];
    }
}