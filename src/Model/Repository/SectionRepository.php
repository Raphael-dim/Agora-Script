<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Section;

class SectionRepository extends AbstractRepository
{
    protected function construire(array $questionTableau) : Section
    {
        $section = new Section(
            $questionTableau["id"],
            $questionTableau["titre"],
            $questionTableau["description"]
        );
        $section->setId($questionTableau["idsection"]);
        return $section;
    }

    protected function getNomTable(): string
    {
        return "Sections";
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return array("id", "titre", "description");

    }
}
