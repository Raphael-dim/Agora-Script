<?php

namespace App\Vote\Model\DataObject;

class Section
{
    private int $idQuestion;
    private int $idSection;
    private string $titre;
    private string $description;

    /**
     * @param int $idQuestion
     * @param int $idSection
     * @param string $titre
     * @param string $description
     */
    public function __construct(int $idQuestion, int $idSection, string $titre, string $description)
    {
        $this->idQuestion = $idQuestion;
        $this->idSection = $idSection;
        $this->titre = $titre;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    /**
     * @param int $idQuestion
     */
    public function setIdQuestion(int $idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }

    /**
     * @return int
     */
    public function getIdSection(): int
    {
        return $this->idSection;
    }

    /**
     * @param int $idSection
     */
    public function setIdSection(int $idSection): void
    {
        $this->idSection = $idSection;
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}
