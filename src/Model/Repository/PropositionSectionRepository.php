<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;

class PropositionSectionRepository extends AbstractRepository
{
    protected function construire(array $propositionTableau) : PropositionSection
    {
        return new PropositionSection(
            (new PropositionRepository())->select($propositionTableau['idproposition']),
            (new SectionRepository())->select($propositionTableau['idsection']),
            $propositionTableau["contenu"]
        );
    }

    protected function getNomTable(): string
    {
        return "Proposition_section";
    }

    protected function getNomClePrimaire(): string
    {
        return "idProposition";
    }

    protected function getNomsColonnes(): array
    {
        return array("idProposition", "idSection", "contenu");

    }
}
