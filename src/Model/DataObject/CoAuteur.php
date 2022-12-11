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


    /**
     * Renvoie vrai si l'utilisateur est un coAuteur de la proposition, faux sinon
     * @param  Utilisateur $utilisateur
     * @param  Proposition $proposition
     * @return bool
     */
    public static function estCoAuteur($utilisateur, $proposition) : bool
    {
        $coAuteurs = (new CoAuteurRepository())->selectWhere(array('clef0' => $utilisateur, 'clef1' => $proposition),"*",array('clef0' => 'idauteur', 'clef1' => 'idproposition'));
        if(!$coAuteurs) return false;
        /*if(is_array($coAuteurs)){
            foreach($coAuteurs as $coAuteur){
                if($coAuteur->getProposition()->getId() == $proposition->getId()){
                    return true;
                }
            }
        }else{
            if($coAuteurs->getProposition()->getId() == $proposition->getId()){
                return true;
            }
        }*/
        return true;
    }

    public function formatTableau(): array
    {
        return array(
            "idauteurTag" => $this->utilisateur->getIdentifiant(),
            "idpropositionTag" => $this->proposition->getId(),
        );
    }
}
