<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\VoteRepository;

class Votant extends Utilisateur
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    public static function estVotant($question, $utilisateur): bool
    {
        $votants = $question->getVotants();
        foreach ($votants as $votant) {
            if ($votant->getIdentifiant() == $utilisateur) {
                return true;
            }
        }
        return false;
    }


    public static function aVote($propositions, $utilisateur): ?int
    {
        $votes = (new VoteRepository())->selectWhere($utilisateur, '*', 'idvotant', 'Votes');
        foreach ($votes as $vote) {
            if (in_array($vote->getProposition(), $propositions)) {
                return $vote->getProposition()->getId();
            }
        }
        return null;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}