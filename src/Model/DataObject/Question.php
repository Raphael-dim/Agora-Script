<?php

namespace App\Vote\Model\DataObject;

class Question extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private string $description;
    private Utilisateur $auteur;
    private Calendrier $calendrier;


    public function __construct(string $titre, string $description, Calendrier $calendrier, Utilisateur $auteur)
    {
        $this->titre = $titre;
        $this->description = $description;
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


    public function formatTableau(): array
    {
        return array(
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description,
            "idCalendrierTag" => $this->calendrier->getIdCalendrier(),
            "idAuteurTag" => $this->auteur->getIdentifiant()
        );
    }
}
