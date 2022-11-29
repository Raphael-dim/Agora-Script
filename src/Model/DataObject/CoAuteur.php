<?php

namespace App\Vote\Model\DataObject;

class CoAuteur extends AbstractDataObject
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

    public static function estCoAuteur($question, $utilisateur) : bool
    {
        $coAuteurs = $question->getCoAuteur();
        foreach ($coAuteurs as $coAuteur){
            if ($coAuteur->getUtilisateur()->getIdentifiant() == $utilisateur){
                return true;
            }
        }
        return false;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->utilisateur->getIdentifiant(),
        );
    }
}
