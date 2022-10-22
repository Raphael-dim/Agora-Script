<?php

namespace App\Vote\Model\DataObject;

class Question extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private int $nbSections;
    private Calendrier $calendrier;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(int $id, string $titre, int $nbSections)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->nbSections = $nbSections;
    }

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


    public function formatTableau(): array
    {
        return array(
        "id" =>  $this->id,
        "titre" =>  $this->titre,
        "nbSections" =>  $this->nbSections
    );
    }
}
