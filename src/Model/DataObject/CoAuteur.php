<?php

namespace App\Vote\Model\DataObject;

class CoAuteur extends AbstractDataObject
{
    private Question $question;
    private Utilisateur $utilisateur;
    private Utilisateur $responsable;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(Question $question, Utilisateur $utilisateur, Utilisateur $responsable)
    {
        $this->question = $question;
        $this->utilisateur = $utilisateur;
        $this->responsable = $responsable;
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

    /**
     * @return Responsable
     */
    public function getResponsable(): Utilisateur
    {
        return $this->responsable;
    }

    public static function estCoAuteur($question, $utilisateur, $responsable) : bool
    {
        $coAuteurs = $question->getCoAuteur();
        foreach ($coAuteurs as $coAuteur){
            if ($coAuteur->getUtilisateur()->getIdentifiant() == $utilisateur && $coAuteur->getResponsable()->getIdentifiant() == $responsable){
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
