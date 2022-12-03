<?php

namespace App\Covoiturage\Lib;

class ConnexionUtilisateur
{
// L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
// À compléter
    }

    public static function estConnecte(): bool
    {
    }// À compléter


    public static function deconnecter(): void
    {
// À compléter
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
// À compléter
    }
}