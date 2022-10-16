<?php

namespace App\Vote\Model\Repository;
use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class UtilisateurRepository extends AbstractRepository
{

    protected function construire( $utilisateurTableau) : Utilisateur
    {
        return new Utilisateur(
            $utilisateurTableau["identifiant"],
            $utilisateurTableau["nom"],
            $utilisateurTableau["prenom"]
        );
    }

    protected function getNomTable(): string
    {
       return "utilisateurs";
    }

    protected function getNomClePrimaire(): string
    {
        return "indentifiant";
    }
}