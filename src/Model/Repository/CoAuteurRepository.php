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
            (new UtilisateurRepository())->select($coAuteurTableau['idauteur']),
            (new PropositionRepository())->select($coAuteurTableau['idproposition']),
        );
    }

    protected function getNomTable(): string
    {
        return "Coauteurs";
    }

    protected function getNomClePrimaire(): string
    {
        return "idauteur";
    }

    protected function getNomsColonnes(): array
    {
        return array("idauteur", "idproposition");
    }
}
