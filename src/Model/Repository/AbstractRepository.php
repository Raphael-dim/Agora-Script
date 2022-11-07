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
        echo $sql;
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
            $pdoStatement->execute($object->formatTableau(true));
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

    public function select($clef): ?AbstractDataObject
    {
        $sql = 'SELECT * from ' . $this->getNomTable() . ' WHERE ' . $this->getNomClePrimaire() . '=:clef';
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "clef" => $clef,
        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);
        // On récupère les résultats comme précédemment
        // Note : fetch() renvoie false si pas d'objet correspondant
        $data = $pdoStatement->fetch();
        if (!$data) {
            return null;
        }
        return $this->construire($data);
    }

    public function selects($clef, $rowSelect = '*', $whereCondition = null, $nomTable = null): array
    {
        $ADonnees = array();
        if (is_null($nomTable)) {
            $sql = 'SELECT ' . $rowSelect . ' from ' . $this->getNomTable();
        } else {
            $sql = 'SELECT ' . $rowSelect . ' from ' . $nomTable;
        }
        if (is_null($whereCondition)) {
            $sql = $sql . ' WHERE ' . $this->getNomClePrimaire() . ' =:clef';
        } else {
            $sql = $sql . ' WHERE ' . $whereCondition . ' =:clef';

        }
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "clef" => $clef,
        );
        $pdoStatement->execute($values);
        foreach ($pdoStatement as $donneesFormatTableau) {
            $ADonnees[] = $this::construire(json_decode(json_encode($donneesFormatTableau), true));


        }

        return $ADonnees;
    }

    protected abstract function getNomTable(): string;

    protected abstract function construire(array $objetFormatTableau);

    protected abstract function getNomClePrimaire(): string;

    protected abstract function getNomsColonnes(): array;
}