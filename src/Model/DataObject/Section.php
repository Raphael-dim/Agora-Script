<?php

namespace App\Vote\Model\DataObject;

class Section extends AbstractDataObject
{
    private int $id;
    private string $titre;
    private string $description;
    private Question $question;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(string $titre, string $description, Question $question)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->question = $question;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
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

    public function formatTableau($update = false): array
    {
        $tab = array(
            "idquestionTag" => $this->question->getId(),
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description
        );
        if ($update) {
            $tab["idsectionTag"] = $this->id;
        }
        return $tab;
    }
}
