<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository
{
    protected function construire(array $questionTableau) : Question
    {
        return new Question(
            $questionTableau["id"],
            $questionTableau["titre"],
            $questionTableau["nbSections"]
        );
    }

    protected function getNomTable(): string
    {
        return "Questions";
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return array("id", "titre", "nbSections");

    }
}
