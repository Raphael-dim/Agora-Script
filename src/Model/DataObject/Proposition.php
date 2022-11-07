<?php

namespace App\Vote\Model\DataObject;

class Proposition extends AbstractDataObject
{
    private string $titre;
    private string $contenu;
    private Utilisateur $auteur;
    private Question $question;


    public function __construct(string $titre, string $contenu, Utilisateur $auteur, Question $question)
    {
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->auteur = $auteur;
        $this->question = $question;
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
     * @return string
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    /**
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
    {
        return $this->auteur;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function formatTableau(): array
    {
        return array(
            "titreTag" => $this->titre,
            "contenuTag" => $this->contenu,
            "idAuteurTag" => $this->auteur->getIdentifiant(),
            "idQuestionTag" => $this->question->getId()
        );
    }
}
