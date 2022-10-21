<?php

namespace App\Vote\Model\DataObject;

abstract class AbstractDataObject
{
    public abstract function formatTableau(): array;
}
