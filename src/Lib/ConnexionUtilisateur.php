<?php

namespace App\Vote\Lib;

use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\UtilisateurRepository;

class ConnexionUtilisateur
{


// L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    private static bool $estAdmin;

    public static function connecter(string $loginUtilisateur): void
    {
        Session::getInstance()->enregistrer('user', array('id' => $loginUtilisateur));
        self::$estAdmin = (new UtilisateurRepository())->select(self::getLoginUtilisateurConnecte())->isEstAdmin();
    }

    public static function estConnecte(): bool
    {
        return isset(Session::getInstance()->lire('user')['id']);
    }

    public static function deconnecter(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            self::$estAdmin = false;
        }
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        if (self::estConnecte()) {
            return Session::getInstance()->lire('user')['id'];
        }
        return null;
    }

    public static function estAdministrateur(): bool
    {
        if (!self::estConnecte()) {
            return false;
        }
        if (self::estConnecte() && isset(self::$estAdmin)) {
            return self::$estAdmin;
        }

        self::$estAdmin = (new UtilisateurRepository())->select(self::getLoginUtilisateurConnecte())->isEstAdmin();
        return self::$estAdmin;
    }
}