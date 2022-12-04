<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Lib\MotDePasse;
use App\Vote\Model\Repository\AbstractRepository;

class Utilisateur extends AbstractDataObject
{
    private string $identifiant;
    private string $nom;
    private string $prenom;
    private string $mdpHache;

    public function __construct(string $identifiant, string $nom, string $prenom, string $mdpHache)
    {
        $this->identifiant = $identifiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mdpHache = $mdpHache;
    }

    public static function construireDepuisFormulaire(array $tableauFormulaire): Utilisateur
    {
        return new Utilisateur($tableauFormulaire['identifiant'], $tableauFormulaire['nom'],

            $tableauFormulaire['prenom'], MotDePasse::hacher($tableauFormulaire['mdp']));
    }

    public function getMdpHache(): string
    {
        return $this->mdpHache;
    }

    public function setMdpHache(string $mdp): void
    {
        $this->mdpHache = MotDePasse::hacher($mdp);
    }

    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): void
    {
        $this->identifiant = $identifiant;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function formatTableau(): array
    {
        return array(
            "identifiantTag" => $this->identifiant,
            "nomTag" => $this->nom,
            "prenomTag" => $this->prenom,
            "mdpTag" => $this->mdpHache
        );
    }
}