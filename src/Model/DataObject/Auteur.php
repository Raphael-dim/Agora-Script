<?php

namespace App\Vote\Model\DataObject;

class Auteur extends AbstractDataObject
{
    private Question $question;
    private Utilisateur $utilisateur;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(Question $question, Utilisateur $utilisateur)
    {
        $this->question = $question;
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }



    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "titreTag" => $this->titre,
            "descriptionTag" => $this->description
        );
    }
}
