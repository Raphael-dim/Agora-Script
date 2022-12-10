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

    public static function aVote($proposition, $utilisateur): ?int
    {
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $utilisateur), '*',
            array('idproposition', 'idvotant'), 'Votes');
        if (isset($vote[0])) {
            return $vote[0]->getValeur();
        } else {
            return null;
        }
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}