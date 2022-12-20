<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
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
    private string $systemeVote;


    public function __construct(string     $titre, string $description, string $creation,
                                Calendrier $calendrier, Utilisateur $organisateur, string $systemeVote)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->creation = $creation;
        $this->calendrier = $calendrier;
        $this->organisateur = $organisateur;
        $this->systemeVote = $systemeVote;
    }

    /**
     * @return string
     */
    public function getSystemeVote(): string
    {
        return $this->systemeVote;
    }

    /**
     * @param string $systemeVote
     */
    public function setSystemeVote(string $systemeVote): void
    {
        $this->systemeVote = $systemeVote;
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

    /* On obtient les sections pour une question*/
    public function getSections(): array
    {
        return (new SectionRepository())->selectWhere($this->id, '*', "idQuestion", "Sections");
    }

    /* On obtient les responsables pour une question*/
    public function getResponsables(): array
    {
        return (new ResponsableRepository())->selectWhere($this->id, '*', "idQuestion", "Responsables");
    }

    /* On obtient les votants pour une question*/
    public function getVotants(): array
    {
        return (new VotantRepository())->selectWhere($this->id, '*', "idQuestion", "Votants");
    }

    /* On obtient les propositions pour une question*/
    public function getPropositions(): array
    {
        return (new PropositionRepository())->selectWhere($this->id, '*', "idQuestion", 'Propositions');
    }

    public function getPropositionsTrie()
    {
        return (new PropositionRepository())->selectWhereTrie($this->id, '*', "idQuestion", 'Propositions');
    }

    /**
     * On obtient la phase en cours pour une question
     * @return string
     * @throws \Exception
     */
    public function getPhase(): string
    {
        $date = date('Y-m-d H:i');
        if ($date < $this->calendrier->getDebutEcriture(true)) {
            return 'debut';
        } else if ($date > $this->calendrier->getDebutEcriture(true) && $date < $this->calendrier->getFinEcriture(true)) {
            return 'ecriture';
        } else if ($date > $this->calendrier->getFinEcriture(true) && $date < $this->calendrier->getDebutVote(true)) {
            return 'entre';
        } else if ($date > $this->calendrier->getDebutVote(true) && $date < $this->calendrier->getFinVote(true)) {
            return 'vote';
        } else {
            return 'fini';
        }
    }


    public function formatTableau($update = false): array
    {
        $tab = array(
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description,
            "creationTag" => $this->creation,
            "idCalendrierTag" => $this->calendrier->getId(),
            "idOrganisateurTag" => $this->organisateur->getIdentifiant(),
            "systemeVoteTag" => $this->systemeVote
        );
        if ($update) {
            $tab["idQuestionTag"] = $this->id;
        }
        return $tab;
    }
}
