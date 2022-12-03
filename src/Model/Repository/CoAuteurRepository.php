<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\CoAuteur;

class CoAuteurRepository extends AbstractRepository
{
    protected function construire(array $coAuteurTableau): CoAuteur
    {
        $coAuteur =  new CoAuteur(
            (new PropositionRepository())->select($coAuteurTableau['idproposition']),
        );
        $coAuteur->setIdentifiant($coAuteurTableau['idauteur']);
        return $coAuteur;
    }

    protected function getNomTable(): string
    {
        return "CoAuteur";
    }

    protected function getNomClePrimaire(): string
    {
        return "idutilisateur";
    }

    protected function getNomsColonnes(): array
    {
        return array("idauteur", "idproposition");

    }
}
