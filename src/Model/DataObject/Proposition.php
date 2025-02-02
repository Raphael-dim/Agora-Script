<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\VoteRepository;

class Proposition extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private string $idResponsable;
    private string $idQuestion;
    private int $nbEtoiles;
    private int $nbVotes;
    private bool $estEliminee;
    private float $votemedian = 0;
    private float $moyenneVote = 0;

    /**
     * @param int $id
     * @param string $titre
     * @param string $idResponsable
     * @param string $idQuestion
     * @param int $nbEtoiles
     * @param int $nbVotes
     * @param bool $estEliminee
     */

    public function __construct(string $titre, string $idResponsable, string $idQuestion, int $nbEtoiles, int $nbVotes, bool $estEliminee)
    {
        /*
       On ne construit pas l'objet proposition avec un objet Responsable et un objet Question pour éviter de
       faire un aller-retour inutile à la base de donnée.
       Cela permet de construire uniquement si besoin le responsable et la question pour une proposition.
       */
        $this->titre = $titre;
        $this->idResponsable = $idResponsable;
        $this->idQuestion = $idQuestion;
        $this->nbEtoiles = $nbEtoiles;
        $this->nbVotes = $nbVotes;
        $this->estEliminee = $estEliminee;
    }

    /**
     * @return float
     */
    public function getMoyenneVote(): float
    {
        return $this->moyenneVote;
    }

    /**
     * @param float $moyenneVote
     */
    public function setMoyenneVote(float $moyenneVote): void
    {
        $this->moyenneVote = $moyenneVote;
    }

    /**
     * @return float
     */
    public function getVoteMedian(): float
    {
        return $this->votemedian;
    }

    /**
     * @param float $votemedian
     */
    public function setVoteMedian(float $votemedian): void
    {
        $this->votemedian = $votemedian;
    }

    /**
     * @return bool
     */
    public function isEstEliminee(): bool
    {
        return $this->estEliminee;
    }


    /**
     * @param bool $estEliminee
     */
    public function setEstEliminee(bool $estEliminee): void
    {
        $this->estEliminee = $estEliminee;
    }

    /**
     * @return int
     */
    public function getNbEtoiles(): int
    {
        return $this->nbEtoiles;
    }

    /**
     * @param int $nbEtoiles
     */
    public function setNbEtoiles(int $nbEtoiles): void
    {
        $this->nbEtoiles = $nbEtoiles;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @return string
     */
    public function getIdQuestion(): string
    {
        return $this->idQuestion;
    }

    /**
     * @param string $idQuestion
     */
    public function setIdQuestion(string $idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }

    /**
     * @return int
     */
    public function getNbVotes(): int
    {
        return $this->nbVotes;
    }

    /**
     * @param int $nbVotes
     */
    public function setNbVotes(int $nbVotes, int $incremente = 0): void
    {
        if ($incremente != 0) {
            $this->nbEtoiles += $incremente;
        } else {
            $this->nbEtoiles = $nbVotes;
        }
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @param String $responsable
     */
    public function setResponsable(string $responsable): void
    {
        $this->idResponsable = $responsable;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdResponsable(): string
    {
        return $this->idResponsable;
    }

    public function getCoAuteurs(): array
    {
        return (new CoAuteurRepository())->selectWhere($this->id, '*', 'idproposition', "Coauteurs");
    }

    public function getContenus()
    {
        return (new PropositionSectionRepository())->selectWhere($this->getId(), '*', 'idproposition', 'Proposition_section');
    }

    public function getVotes()
    {
        return (new VoteRepository())->selectWhere($this->id, '*',
            'idProposition', 'Votes', 'valeurvote');
    }

    public function formatTableau($update = false): array
    {
        $estEliminee = $this->estEliminee;
        if (!$estEliminee) {
            $estEliminee = 0;
        }
        $tab = array(
            "idquestionTag" => $this->idQuestion,
            "idresponsableTag" => $this->idResponsable,
            "titreTag" => $this->titre,
            "nbvotesTag" => $this->nbVotes,
            "nbetoilesTag" => $this->nbEtoiles,
            "estElimineeTag" => $estEliminee
        );
        if ($update) {
            $tab["idpropositionTag"] = $this->id;
        }
        return $tab;
    }
}
