<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DatabaseConnection as DatabaseConnection;
use App\Vote\Model\DataObject\AbstractDataObject;
use PDOException;

abstract class AbstractRepository
{
    /*
     * Sauvegarde l'objet dans la base de données
     * en récupérant les colonnes et la table associés à la classe
     */
    public function sauvegarder(AbstractDataObject $object): ?int
    {
        $sql = "INSERT INTO " . $this->getNomTable();
        $sql = $sql . " (";
        foreach ($this->getNomsColonnes() as $colonne) {
            $sql = $sql . $colonne . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql = $sql . ") VALUES (";
        foreach ($this->getNomsColonnes() as $colonne) {
            $sql = $sql . ":" . $colonne . "Tag, ";
        }
        $sql = substr($sql, 0, -2);
        $sql = $sql . ");";
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        // On donne les valeurs et on exécute la requête
        try {
            $pdoStatement->execute($object->formatTableau());
        } catch (PDOException $e) {
            echo($e->getMessage());
            return null;
        }
        $id = DatabaseConnection::getPdo()->query("SELECT MAX(" . $this->getNomClePrimaire() . ") FROM " . $this->getNomTable());
        foreach ($id as $retour) {
            $max = $retour[0];
            return $max;
        }
        return null;
    }

    /*
     * Supprime la ligne de la base de données graçe à la clef primaire
     */
    public function delete(string $valeurClePrimaire)
    {
        $sql = "DELETE FROM " . $this->getNomTable() . " WHERE " . $this->getNomClePrimaire() . " =:clePrimaireTag";
        $value = array(
            "clePrimaireTag" => $valeurClePrimaire
        );
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        try {
            $pdoStatement->execute($value);
        } catch (PDOException $e) {
            echo($e->getMessage());
        }
    }

    /*
     * met à jour l'objet dans la base de données
     */
    public function update(AbstractDataObject $object)
    {
        $sql = "UPDATE " . $this->getNomTable() . "
                SET ";
        foreach ($this->getNomsColonnes() as $colonne) {
            $sql = $sql . $colonne . " =:" . $colonne . "Tag, ";
        }
        $sql = substr($sql, 0, -2);
        $sql = $sql . " WHERE " . $this->getNomClePrimaire() . "=:" . $this->getNomClePrimaire() . "Tag;";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        // On donne les valeurs et on exécute la requête
        try {
            $pdoStatement->execute($object->formatTableau());
        } catch (PDOException $e) {
            echo($e->getMessage());
        }
    }

    /*
     * Selectionne toute les lignes de la table associé à la classe
     */
    public function selectAll(): array
    {
        $ADonnees = array();
        $pdoStatement = DatabaseConnection::getPdo()->query('SELECT * FROM ' . ($this->getNomTable()));

        foreach ($pdoStatement as $donneesFormatTableau) {

            $ADonnees[] = $this::construire(json_decode(json_encode($donneesFormatTableau), true));
        }
        return $ADonnees;
    }


    /*
     * Selectionne les lignes par rapport à un mot clef
     */
    public function selectKeyword($motclef, $row)
    {
        $ADonnees = array();
        $sql = 'SELECT * from ' . $this->getNomTable() . ' WHERE LOWER(' . $row . ') LIKE LOWER(:motclef) ';
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "motclef" => $motclef . '%',
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $donneesFormatTableau) {
            $ADonnees[] = $this::construire(json_decode(json_encode($donneesFormatTableau), true));
        }

        return $ADonnees;
    }


    /*
     * Selectionne une ligne par rapport à la clef primaire
     */

    public function select($clef,$rowSelect = '*', $whereCondition = null)
    {
        $ADonnees = array();
        $sql = 'SELECT ' . $rowSelect . ' from ' . $this->getNomTable();
        if(is_null($whereCondition)){
            $sql = $sql . ' WHERE '. $this->getNomClePrimaire() . ' =:clef';
        }else{
            $sql = $sql . ' WHERE '. $whereCondition . ' =:clef';
        }
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "clef" => $clef,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $donneesFormatTableau) {
            if ($pdoStatement->rowCount() > 1) {
                $ADonnees[] = $this::construire(json_decode(json_encode($donneesFormatTableau), true));
            } else {
                return $this::construire(json_decode(json_encode($donneesFormatTableau), true));
            }
        }
        //var_dump($ADonnees);
        return $ADonnees;
    }

    /*
     * Retourne le nom de la table associé à la classe
     */
    protected abstract function getNomTable(): string;

    /*
     * Transforme un tableau de donnée en Objet
     */
    protected abstract function construire(array $objetFormatTableau);

    /*
     * Retourne le nom de la clef primaire de la table
     */
    protected abstract function getNomClePrimaire(): string;

    /*
     * Retourne les noms de colonnes de la table
     */
    protected abstract function getNomsColonnes(): array;
}