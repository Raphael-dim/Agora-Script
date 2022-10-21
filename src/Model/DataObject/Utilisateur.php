<?php
namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;

class Utilisateur extends AbstractDataObject
{
    private string $identifiant;
    private string $nom;
    private string $prenom;

    /**
     * @param string $identifiant
     * @param string $nom
     * @param string $prenom
     */
    public function __construct(string $identifiant, string $nom, string $prenom)
    {
        $this->identifiant = $identifiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    /**
     * @param string $identifiant
     */
    public function setIdentifiant(string $identifiant): void
    {
        $this->identifiant = $identifiant;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }


    public function formatTableau(): array
    {
        return array(
            "identifiant" =>  $this->identifiant,
            "nom" =>  $this->nom,
            "prenom" =>  $this->prenom);
    }
}