<?php

namespace App\Vote\Model\DataObject;

abstract class AbstractDataObject
{
    /*
     * Retourne un Objet en tableau de données
     */
    public abstract function formatTableau(): array;
}
