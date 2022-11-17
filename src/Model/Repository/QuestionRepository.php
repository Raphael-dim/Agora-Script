<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DatabaseConnection as DatabaseConnection;
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

    public function getPhaseVote(): array
    {
        $ADonnees = array();
        $sql = "SELECT * FROM questions_vote";
        $pdoStatement = DatabaseConnection::getPdo()->query($sql);
        while ($row = $pdoStatement->fetch()) {
            $ADonnees[] = $this::construire(json_decode(json_encode($row), true));
        }
        return $ADonnees;
    }

    public function getPhaseEcriture(): array
    {
        $ADonnees = array();
        $sql = "SELECT * FROM questions_ecriture";
        $pdoStatement = DatabaseConnection::getPdo()->query($sql);
        while ($row = $pdoStatement->fetch()) {
            $ADonnees[] = $this::construire(json_decode(json_encode($row), true));
        }
        return $ADonnees;
    }

    public function getTerminees(): array
    {
    $ADonnees = array();
    $sql = "SELECT * FROM questions_termines";
    $pdoStatement = DatabaseConnection::getPdo()->query($sql);
    while ($row = $pdoStatement->fetch()) {
        $ADonnees[] = $this::construire(json_decode(json_encode($row), true));
    }
    return $ADonnees;
}
}
