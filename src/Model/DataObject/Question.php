<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\SectionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\VotantRepository;

class Question extends AbstractDataObject
{

    private int $id;
    private string $titre;
    private string $description;
    private string $creation;
    private Utilisateur $organisateur;
    private Calendrier $calendrier;


    public function __construct(string $titre, string $description, string $creation,
                                Calendrier $calendrier, Utilisateur $organisateur)
    {
        $this->titre = $titre;
        $this->description = $description;

        $this->creation = $creation;

        $this->calendrier = $calendrier;
        $this->organisateur = $organisateur;
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
     * @return Utilisateur
     */
    public function getOrganisateur(): Utilisateur
    {
        return $this->organisateur;
    }

    /**
     * @param Utilisateur $organisateur
     */
    public function setOrganisateur(Utilisateur $organisateur): void
    {
        $this->organisateur = $organisateur;
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
        return (new SectionRepository())->selectWhere($this->id, '*', "idQuestion", "Sections");
    }

    public function getResponsables(): array
    {
        return (new ResponsableRepository())->selectWhere($this->id, '*', "idQuestion", "Responsables");
    }

    public function getVotants(): array
    {
        return (new VotantRepository())->selectWhere($this->id, '*', "idQuestion", "Votants");
    }


    public function formatTableau($update = false): array
    {
        $tab = array(
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description,
            "creationTag" => $this->creation,
            "idCalendrierTag" => $this->calendrier->getId(),
            "idOrganisateurTag" => $this->organisateur->getIdentifiant()
        );
        if ($update) {
            $tab["idQuestionTag"] = $this->id;
        }
        return $tab;
    }
}
