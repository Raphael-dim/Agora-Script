<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\PropositionSectionRepository;

class Proposition extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private Responsable $responsable;
    private Question $question;
    private int $nbVotes;

    public function __construct(string $titre, Responsable $responsable, Question $question, int $nbVotes)
    {
        $this->titre = $titre;
        $this->responsable = $responsable;
        $this->question = $question;
        $this->nbVotes = $nbVotes;
    }

    public function getTitre(): string
    {
        return $this->titre;
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
    public function setNbVotes(int $nbVotes): void
    {
        $this->nbVotes = $nbVotes;
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
     * @param Utilisateur $responsable
     */
    public function setResponsable(Responsable $responsable): void
    {
        $this->responsable = $responsable;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    public function getResponsable(): Responsable
    {
        return $this->responsable;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getContenus()
    {
        return (new PropositionSectionRepository())->select($this->question->getId());
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idresponsableTag" => $this->responsable->getIdentifiant(),
            "titreTag" => $this->titre,
            "nbvotesTag" => $this->nbVotes,
            "idpropositionTag" => $this->id
        );
    }
}
