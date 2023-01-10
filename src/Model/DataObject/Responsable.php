<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\ResponsableRepository;

class Responsable extends Utilisateur
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    /* On vérifie si l'utilisateur est responsable pour une question*/
    public static function estResponsable(string $question, $utilisateur): bool
    {
        $Responsable = (new ResponsableRepository())->selectWhere(array('clef0' => $utilisateur, 'clef1' => $question),"*",array('clef0' => 'idutilisateur', 'clef1' => 'idquestion'));
        if(!$Responsable) return false;
        return true;
    }

    /* On vérifie si le responsable a déjà crée une proposition pour une question*/
    public static function aCreeProposition($question, $utilisateur): bool
    {
        $propositions = (new PropositionRepository())->selectWhere($utilisateur, '*',
            'idresponsable', 'Propositions');
        foreach ($propositions as $proposition) {
            if ($question->getId() == $proposition->getIdQuestion()) {
                return true;
            }
        }
        return false;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}