<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\SectionRepository;

class Question extends AbstractDataObject
{

    private int $id;
    private string $titre;
    private string $description;
    private string $creation;
    private Utilisateur $auteur;
    private Calendrier $calendrier;


    public function __construct(string $titre, string $description, string $creation, Calendrier $calendrier, Utilisateur $auteur)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->creation = $creation;
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

    /**
     * @return string
     */
    public function getCreation(): string
    {
        $date = date_create($this->creation);
        return date_format($date, 'd/m/Y H:i:s');
    }

    /**
     * @param string $creation
     */
    public function setCreation(string $creation): void
    {
        $this->creation = $creation;
    }

    /**
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
    {
        return $this->auteur;
    }

    /**
     * @param Utilisateur $auteur
     */
    public function setAuteur(Utilisateur $auteur): void
    {
        $this->auteur = $auteur;
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

    public function getSections(): array
    {
        $sections = (new SectionRepository())->select($this->id, "idQuestion");
        var_dump($sections);
        return $this->getSections();
    }


    public function formatTableau(): array
    {
        return array(
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description,
            "creationTag" => $this->creation,
            "idCalendrierTag" => $this->calendrier->getIdCalendrier(),
            "idAuteurTag" => $this->auteur->getIdentifiant()
        );
    }
}
