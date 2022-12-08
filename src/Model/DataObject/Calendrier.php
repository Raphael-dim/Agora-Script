<?php

namespace App\Vote\Model\DataObject;


use Cassandra\Date;
use DateTime;

class Calendrier extends AbstractDataObject
{
    private int $id;
    private string $debutEcriture;
    private string $finEcriture;
    private string $debutVote;
    private string $finVote;

    /**
     * @param string $debutEcriture
     * @param string $finEcriture
     * @param string $debutVote
     * @param string $finVote
     */
    public function __construct(string $debutEcriture, string $finEcriture, string $debutVote, string $finVote)
    {
        $this->debutEcriture = $debutEcriture;
        $this->finEcriture = $finEcriture;
        $this->debutVote = $debutVote;
        $this->finVote = $finVote;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDebutEcriture($bool = false): string
    {
        if ($bool){
            return (new DateTime($this->debutEcriture))->format('Y-m-d H:i');
        }
        return (new DateTime($this->debutEcriture))->format('d-m-Y à H:i:s');
    }

    /**
     * @param string $debutEcriture
     */
    public function setDebutEcriture(string $debutEcriture): void
    {
        $this->debutEcriture = $debutEcriture;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFinEcriture($bool = false): string
    {
        if ($bool){
            return (new DateTime($this->finEcriture))->format('Y-m-d H:i');
        }
        return (new DateTime($this->finEcriture))->format('d/m/Y à H:i:s');
    }

    /**
     * @param string $finEcriture
     */
    public function setFinEcriture(string $finEcriture): void
    {
        $this->finEcriture = $finEcriture;
    }

    /**
     * @return string
     */
    public function getDebutVote($bool = false): string
    {
        if ($bool){
            return (new DateTime($this->debutVote))->format('Y-m-d H:i');
        }
        else{
            return (new DateTime($this->debutVote))->format('d/m/Y à H:i:s');
        }

    }

    /**
     * @param string $debutVote
     */
    public function setDebutVote(string $debutVote): void
    {
        $this->debutVote = $debutVote;
    }

    /**
     * @return string
     */
    public function getFinVote($bool = false): string
    {
        if ($bool){
            return (new DateTime($this->finVote))->format('Y-m-d H:i');
        }
        return (new DateTime($this->finVote))->format('d/m/Y à H:i:s');
    }

    /**
     * @param string $finVote
     */
    public function setFinVote(string $finVote): void
    {
        $this->finVote = $finVote;
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


    public function formatTableau($update = false): array
    {
        $tab = array(
            "debutEcritureTag" => $this->debutEcriture,
            "finEcritureTag" => $this->finEcriture,
            "debutVoteTag" => $this->debutVote,
            "finVoteTag" => $this->finVote
        );
        if ($update) {
            $tab['idCalendrierTag'] = $this->id;
        }
        return $tab;
    }
}