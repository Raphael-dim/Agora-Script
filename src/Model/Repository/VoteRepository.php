<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\Vote;

class VoteRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
         return "Votes";
    }

    protected function construire(array $voteFormatTableau)
    {
        $vote = new Vote(
            (new VotantRepository())->select($voteFormatTableau['idvotant']),
            (new PropositionRepository())->select($voteFormatTableau['idproposition']),
            $voteFormatTableau['valeurvote']
        );
        $vote->setId($voteFormatTableau["idvote"]);
        return $vote;
    }

    protected function getNomClePrimaire(): string
    {
        return "idvote";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idvotant",
            "idproposition",
            "valeurvote"
        );
    }
}