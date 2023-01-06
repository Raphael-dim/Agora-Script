<?php

namespace App\Vote\Model\DataObject;


use Cassandra\Date;
use DateInterval;
use DateTime;

class Calendrier extends AbstractDataObject
{
    private Question $question;
    private int $id;
    private string $debutEcriture;
    private string $finEcriture;
    private string $debutVote;
    private string $finVote;

    /*
     * @param string $debutEcriture
     * @param string $finEcriture
     * @param string $debutVote
     * @param string $finVote
     */
    public function __construct(Question $question, ?string $debutEcriture, ?string $finEcriture, string $debutVote, string $finVote)
    {
        $this->question = $question;
        if (!is_null($debutEcriture))
            $this->debutEcriture = $debutEcriture;
        if(!is_null($finEcriture))
            $this->finEcriture = $finEcriture;
        $this->debutVote = $debutVote;
        $this->finVote = $finVote;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }


    /**
     * La base de donnée gère les dates dans un format différent, il faut donc convertir ce dernier
     * dans chaque Getter
     * @return ?string
     * @throws \Exception
     */
    public function getDebutEcriture($bool = false): ?string
    {
        if (!isset($this->debutEcriture)) {
            return null;
        }
        if ($this->debutEcriture == "") {
            return "";
        }
        if ($bool) {
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
     * @throws \Exception
     */
    public function getFinEcriture($bool = false): ?string
    {
        if (!isset($this->finEcriture)) {
            return null;
        }
        if ($this->finEcriture == "") {
            return "";
        }
        if ($bool) {
            return (new DateTime($this->finEcriture))->format('Y-m-d H:i');
        }
        return (new DateTime($this->finEcriture))->format('d-m-Y à H:i:s');
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
     * @throws \Exception
     */
    public function getDebutVote($bool = false): string
    {
        if ($bool) {
            return (new DateTime($this->debutVote))->format('Y-m-d H:i');
        } else {
            return (new DateTime($this->debutVote))->format('d-m-Y à H:i:s');
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
     * @throws \Exception
     */
    public function getFinVote($bool = false): string
    {
        if ($bool) {
            return (new DateTime($this->finVote))->format('Y-m-d H:i');
        }
        return (new DateTime($this->finVote))->format('d-m-Y à H:i:s');
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


    /**
     * Méthode qui permet d'afficher en détail un interval de date, n'a rien à voir avec l'objet calendrier
     * mais en rapport avec le temps, les valeurs à 0 comme les années ne sont pas affichées
     * @param DateInterval $interval
     * @return string
     */

    public static function diff(DateInterval $interval): string
    {
        $res = "";
        if ($interval->y != 0) {
            $res = $res . $interval->y . ' année, ';
        }
        if ($interval->m != 0) {
            $res = $res . $interval->m . ' mois, ';
        }
        if ($interval->d != 0) {
            $res = $res . $interval->d . ' jours, ';
        }
        if ($interval->h != 0) {
            $res = $res . $interval->h . ' heures, ';
        }
        if ($interval->i != 0) {
            $res = $res . $interval->i . ' minutes';
        }
        return $res;
    }


    public function formatTableau($update = false): array
    {
        if ($this->debutEcriture == "" && $this->finEcriture == "") {
            $tab = array(
                "idQuestionTag" => $this->question->getId(),
                "debutEcritureTag" => null,
                "finEcritureTag" => null,
                "debutVoteTag" => $this->debutVote,
                "finVoteTag" => $this->finVote
            );
        } else{
            $tab = array(
                "idQuestionTag" => $this->question->getId(),
                "debutEcritureTag" => $this->debutEcriture,
                "finEcritureTag" => $this->finEcriture,
                "debutVoteTag" => $this->debutVote,
                "finVoteTag" => $this->finVote
            );
        }
        if ($update) {
            $tab['idCalendrierTag'] = $this->id;
        }
        return $tab;
    }
}