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
    private bool $estAdmin;
    private string $email;
    private string $emailAValider;
    private string $nonce;

    public function __construct(string $identifiant, string $nom, string $prenom, string $mdpHache, bool $estAdmin, string $email, string $emailAValider, string $nonce)
    {
        $this->identifiant = $identifiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mdpHache = $mdpHache;
        $this->estAdmin = $estAdmin;
        $this->email = $email;
        $this->emailAValider = $emailAValider;
        $this->nonce = $nonce;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    /**
     * @param string $emailAValider
     */
    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     */
    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }


    /**
     * @return bool
     */
    public function isEstAdmin(): bool
    {
        return $this->estAdmin;
    }

    /**
     * @param bool $estAdmin
     */
    public function setEstAdmin(bool $estAdmin): void
    {
        $this->estAdmin = $estAdmin;
    }

    public static function construireDepuisFormulaire(array $tableauFormulaire): Utilisateur
    {
        if (isset($tableauFormulaire['estAdmin'])) {
            $estAdmin = 1;
        } else {
            $estAdmin = 0;
        }
        return new Utilisateur($tableauFormulaire['identifiant'], $tableauFormulaire['nom'],
            $tableauFormulaire['prenom'], MotDePasse::hacher($tableauFormulaire['mdp']),
            $estAdmin, "", $tableauFormulaire['email'], MotDePasse::genererChaineAleatoire());
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
        $estAdmin = $this->estAdmin;
        if (!$estAdmin) {
            $estAdmin = 0;
        }
        return array(
            "identifiantTag" => $this->identifiant,
            "nomTag" => $this->nom,
            "prenomTag" => $this->prenom,
            "mdpTag" => $this->mdpHache,
            "estAdminTag" => $estAdmin,
            "emailTag" => $this->email,
            "emailAValiderTag" => $this->emailAValider,
            "nonceTag" => $this->nonce
        );
    }
}