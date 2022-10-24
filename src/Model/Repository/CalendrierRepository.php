<?php

namespace App\Vote\Model\Repository;


use App\Vote\Model\DataObject\Calendrier;

class CalendrierRepository extends AbstractRepository
{
    protected function construire(array $calendrierTableau) : Calendrier
    {
        return new Calendrier(
            $calendrierTableau["debutEcriture"],
            $calendrierTableau["finEcriture"],
            $calendrierTableau["debutVote"],
            $calendrierTableau["finVote"],
        );
    }

    protected function getNomTable(): string
    {
        return "Calendriers";
    }

    protected function getNomClePrimaire(): string
    {
        return "idCalendrier";
    }

    protected function getNomsColonnes(): array
    {
        return array("debutEcriture", "finEcriture", "debutVote", "finVote", "idCalendrier");
    }
}
