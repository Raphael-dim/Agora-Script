<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\CoAuteurRepository;

class CoAuteur extends AbstractDataObject
{
    private Utilisateur $utilisateur;
    private Proposition $proposition;

    /**
     * @param int $id
     * @param string $titre
     * @param int $nbSections
     */
    public function __construct(Utilisateur $utilisateur, Proposition $proposition)
    {
        $this->utilisateur = $utilisateur;
        $this->proposition = $proposition;
    }

    /**
     * @return Question
     */
    public function getProposition(): Proposition
    {
        return $this->proposition;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public static function estCoAuteur($utilisateur, $proposition) : bool
    {
        $coAuteurs = (new CoAuteurRepository())->select($utilisateur);
        foreach($coAuteurs as $coAuteur){
            if($coAuteur->getProposition()->getId() == $proposition->getId()){
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
