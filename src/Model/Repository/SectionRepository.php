<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Section;

class SectionRepository extends AbstractRepository
{
    protected function construire(array $questionTableau): Section
    {
        $section = new Section(
            $questionTableau["titre"],
            $questionTableau["description"],
            (new QuestionRepository())->select($questionTableau['idquestion'])
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
        return "idsection";
    }

    protected function getNomsColonnes(): array
    {
        return array("idquestion", "titre", "description");

    }
}
