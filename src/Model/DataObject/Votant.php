<?php
namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;

class Votant extends Utilisateur
{
    private Question $question;
    private Utilisateur $utilisateur;

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

}