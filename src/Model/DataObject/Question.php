<?php

namespace App\Vote\Model\DataObject;

class Question
{
    private string $titre;
    private int $nbSections;
    private array $sections;

    /**
     * @param string $titre
     * @param int $nbSections
     * @param array $sections
     */
    public function __construct(string $titre, int $nbSections, array $sections)
    {
        $this->titre = $titre;
        $this->nbSections = $nbSections;
        $this->sections = $sections;
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
     * @return int
     */
    public function getNbSections(): int
    {
        return $this->nbSections;
    }

    /**
     * @param int $nbSections
     */
    public function setNbSections(int $nbSections): void
    {
        $this->nbSections = $nbSections;
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param array $sections
     */
    public function setSections(array $sections): void
    {
        $this->sections = $sections;
    }


}
