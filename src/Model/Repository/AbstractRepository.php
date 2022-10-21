<?php

namespace App\Vote\Model\Repository;
use App\Vote\Model\DatabaseConnection as DatabaseConnection;
use App\Vote\Model\DataObject\AbstractDataObject;
use PDOException;

abstract class AbstractRepository
{

    public function sauvegarder(AbstractDataObject $object): bool
    {
        $sql = "INSERT INTO " . $this->getNomTable() . "
                VALUES (";
        foreach ($this->getNomsColonnes() as $colonne) {
            $sql = $sql . ":" . $colonne . "Tag, ";
        }
        $sql = substr($sql, 0, -2);
        $sql = $sql . ")";
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        // On donne les valeurs et on exécute la requête
        try {
            $pdoStatement->execute($object->formatTableau());
        } catch (PDOException $e) {
            echo($e->getMessage());
            return false;
        }
        return true;
    }

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

    public function selectAll(): array
    {
        $ADonnees = array();
        $pdoStatement = DatabaseConnection::getPdo()->query('SELECT * FROM '.($this->getNomTable()));

        foreach ($pdoStatement as $donneesFormatTableau) {

            $ADonnees[] = $this::construire(json_decode(json_encode($donneesFormatTableau), true));
        }
        return $ADonnees;
    }

    public function selectKeyword($motclef,$row)
    {
        $ADonnees = array();
        $sql = 'SELECT * from '.$this->getNomTable() .' WHERE LOWER('.$row .') LIKE LOWER(:motclef) ';
        // Préparation de la requête
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "motclef" => $motclef.'%',
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
    protected abstract function getNomsColonnes() : array;
}