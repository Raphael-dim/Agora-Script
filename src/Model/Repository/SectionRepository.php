<?php

namespace App\Vote\Model\Repository;

class SectionRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
        return "Sections";
    }

    protected function construire(array $objetFormatTableau)
    {
        // TODO: Implement construire() method.
    }

    protected function getNomClePrimaire(): string
    {
        return "idSection";
    }
}