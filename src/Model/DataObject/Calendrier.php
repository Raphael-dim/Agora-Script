<?php

namespace App\Vote\Model\DataObject;


class Calendrier
{
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
     */
    public function getDebutEcriture(): string
    {
        return $this->debutEcriture;
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
     */
    public function getFinEcriture(): string
    {
        return $this->finEcriture;
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
    public function getDebutVote(): string
    {
        return $this->debutVote;
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
    public function getFinVote(): string
    {
        return $this->finVote;
    }

    /**
     * @param string $finVote
     */
    public function setFinVote(string $finVote): void
    {
        $this->finVote = $finVote;
    }



}