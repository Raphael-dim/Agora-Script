<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;

class VoteRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
         return "vote";
    }

    protected function construire(array $voteFormatTableau)
    {
        $vote = new Vote(
            (new VotantRepository())->select($voteFormatTableau['idutilisateur']),
            (new PropositionRepository())->select($voteFormatTableau['idproposition'])
        );
        $vote->setId($voteFormatTableau["idvote"]);
        return $vote;
    }

    protected function getNomClePrimaire(): string
    {
        return "idVote";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idVotant",
            "idProposition"
        );
    }
}