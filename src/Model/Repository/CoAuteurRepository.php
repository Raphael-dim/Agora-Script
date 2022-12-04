<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\CoAuteur;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Utilisateur;

class CoAuteurRepository extends AbstractRepository
{
    protected function construire(array $coAuteurTableau): CoAuteur
    {
        return new CoAuteur(
            (new UtilisateurRepository())->select($coAuteurTableau['idutilisateur']),
            (new PropositionRepository())->select($coAuteurTableau['idproposition']),
        );
    }

    protected function getNomTable(): string
    {
        return "Co-Auteurs";
    }

    protected function getNomClePrimaire(): string
    {
        return "idutilisateur";
    }

    protected function getNomsColonnes(): array
    {
        return array("idutilisateur", "idproposition");
    }
}
