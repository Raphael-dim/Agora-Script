<?php

namespace App\Vote\Model\DataObject;

class Proposition extends AbstractDataObject
{
    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    private string $titre;
    private Responsable $responsable;
    private Question $question;


    public function __construct(string $titre, Responsable $responsable, Question $question)
    {
        $this->titre = $titre;
        $this->responsable = $responsable;
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
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

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idresponsableTag" => $this->responsable->getIdentifiant(),
            "titreTag" => $this->titre
        );
    }
}
