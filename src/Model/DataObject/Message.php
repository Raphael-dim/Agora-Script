<?php


namespace App\Vote\Model\DataObject;

class Message extends AbstractDataObject
{
    private Utilisateur $auteur;
    private Utilisateur $destinataire;
    private string $contenu;
    private string $date;
    private bool $estVu;
    private int $id;

    /**
     * @param Utilisateur $auteur
     * @param Utilisateur $destinataire
     * @param string $contenu
     * @param string $date
     * @param bool $estVu
     */
    public function __construct(Utilisateur $auteur, Utilisateur $destinataire, string $contenu, string $date, bool $estVu)
    {
        $this->auteur = $auteur;
        $this->destinataire = $destinataire;
        $this->contenu = $contenu;
        $this->date = $date;
        $this->estVu = $estVu;
    }

    /**
     * @return bool
     */
    public function isEstVu(): bool
    {
        return $this->estVu;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setEstVu(bool $estVu): void
    {
        $this->estVu = $estVu;
    }

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

    public function formatTableau($update = false): array
    {
        $estVu = 0;
        if ($this->estVu) {
            $estVu = 1;
        }
        $tab =  array(
            "idAuteurTag" => $this->auteur->getIdentifiant(),
            "idDestinataireTag" => $this->destinataire->getIdentifiant(),
            "contenuTag" => $this->contenu,
            "dateTag" => $this->date,
            "estVuTag" => $estVu
        );
        if($update) {
            $tab["idMessageTag"] = $this->id;
        }
        return $tab;
    }
}