<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Lib\MotDePasse;
use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

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

    /**
     * Renvoie vrai si l'identifiant est celui d'un utilisateur existant, faux sinon
     * @param $identifiant
     * @return bool
     */
    public static function identifiantExiste($identifiant): bool
    {
        $utilisateurs = (new UtilisateurRepository())->selectAll();
        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur->getIdentifiant() == $identifiant) {
                return true;
            }
        }
        return false;
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