<?php

namespace App\Vote\Model\DataObject;

class PropositionSection extends AbstractDataObject
{
    private Auteur $auteur;
    private Proposition $proposition;
    private Section $section;
    private string $contenu;

    /**
     * @return Auteur
     */
    public function getAuteur(): Auteur
    {
        return $this->auteur;
    }

    /**
     * @param Auteur $auteur
     */
    public function setAuteur(Auteur $auteur): void
    {
        $this->auteur = $auteur;
    }


    /**
     * @param Proposition $proposition
     * @param Section $section
     * @param string $contenu
     */
    public function __construct(Proposition $proposition, Section $section, string $contenu)
    {
//        $this->auteur = $auteur;
        $this->proposition = $proposition;
        $this->section = $section;
        $this->contenu = $contenu;
    }

    /**
     * @return Proposition
     */
    public function getProposition(): Proposition
    {
        return $this->proposition;
    }

    /**
     * @param Proposition $proposition
     */
    public function setProposition(Proposition $proposition): void
    {
        $this->proposition = $proposition;
    }

    /**
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * @param Section $section
     */
    public function setSection(Section $section): void
    {
        $this->section = $section;
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
//            "idAuteurTag" =>$this->auteur->getId(),
            "idPropositionTag" => $this->proposition->getId(),
            "idSectionTag" => $this->section->getId(),
            "contenuTag" => $this->contenu
        );
    }
}
