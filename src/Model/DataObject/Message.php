<?php


namespace App\Vote\Model\DataObject;

class Message extends AbstractDataObject
{
    private Utilisateur $auteur;
    private Utilisateur $destinataire;
    private string $contenu;
    private string $date;

    /**
     * @param Utilisateur $auteur
     * @param Utilisateur $destinataire
     * @param string $contenu
     */
    public function __construct(Utilisateur $auteur, Utilisateur $destinataire, string $contenu, string $date)
    {
        $this->auteur = $auteur;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->date = $date;
    }

    /**
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
    {
        return $this->auteur;
    }


    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @param Utilisateur $auteur
     */
    public function setAuteur(Utilisateur $auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * @return Utilisateur
     */
    public function getDestinataire(): Utilisateur
    {
        return $this->destinataire;
    }

    /**
     * @param Utilisateur $destinataire
     */
    public function setDestinataire(Utilisateur $destinataire): void
    {
        $this->destinataire = $destinataire;
    }

    /**
     * @return string
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function formatTableau(): array
    {
        return array(
            "auteurTag" => $this->auteur->getIdentifiant(),
            "destinataireTag" => $this->destinataire->getIdentifiant(),
            "contenuTag" => $this->contenu,
            "dateTag" => $this->date
        );
    }
}