<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository
{
    protected function construire(array $questionTableau): Question
    {
        $question = new Question(
            $questionTableau["titre"],
            $questionTableau["description"],
            $questionTableau['creation'],
            (new CalendrierRepository)->select($questionTableau["idcalendrier"]),
            (new UtilisateurRepository)->select($questionTableau["idorganisateur"])
        );
        $question->setId($questionTableau["idquestion"]);
        return $question;
    }

    protected function getNomTable(): string
    {
        return "Questions";
    }

    protected function getNomClePrimaire(): string
    {
        return "idQuestion";
    }

    protected function getNomsColonnes(): array
    {
        return array("titre", "description", "creation", "idCalendrier", "idOrganisateur");

    }
}
