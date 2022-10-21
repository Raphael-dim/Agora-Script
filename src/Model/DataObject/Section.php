<?php

namespace App\Vote\Model\DataObject;

class Section extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private int $description;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(int $id, string $titre, int $description)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
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
     * @return int
     */
    public function getDescription(): int
    {
        return $this->description;
    }

    /**
     * @param int $description
     */
    public function setDescription(int $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function formatTableau(): array
    {
        return array(
            "id" =>  $this->id,
            "titre" =>  $this->titre,
            "description" =>  $this->description
        );
    }
}
