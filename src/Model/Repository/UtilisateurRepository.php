<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class UtilisateurRepository extends AbstractRepository
{

    protected function construire(array $utilisateurTableau): Utilisateur
    {
        return new Utilisateur(
            $utilisateurTableau["identifiant"],
            $utilisateurTableau["nom"],
            $utilisateurTableau["prenom"],
            $utilisateurTableau["mdp"]
        );
    }

    protected function getNomTable(): string
    {
        return "Utilisateurs";
    }

    protected function getNomClePrimaire(): string
    {
        return "identifiant";
    }

    protected function getNomsColonnes(): array
    {
        return array("identifiant", "nom", "prenom", "mdp");
    }
}