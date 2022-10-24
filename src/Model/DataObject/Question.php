<?php

namespace App\Vote\Model\DataObject;

class Question extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private string $description;
    private Utilisateur $auteur;
    private Calendrier $calendrier;
    private int $nbSections;


    public function __construct(string $titre, string $description, int $nbSections, Calendrier $calendrier, Utilisateur $auteur)
    {
        $this->id = 1;
        $this->titre = $titre;
        $this->description = "blabla";
        $this->nbSections = $nbSections;
        $this->calendrier = $calendrier;
        $this->auteur = $auteur;
    }

    /**
     * @return Calendrier
     */
    public function getCalendrier(): Calendrier
    {
        return $this->calendrier;
    }

    /**
     * @param Calendrier $calendrier
     */
    public function setCalendrier(Calendrier $calendrier): void
    {
        $this->calendrier = $calendrier;
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
            "id" => $this->id,
            "titre" => $this->titre,
            "description" => $this->description,
            "nbSections" => $this->nbSections,
            "calendrier" => $this->calendrier,
            "auteur" => $this->auteur
        );
    }
}
