<?php

namespace App\Vote\Config;
class Conf
{

    static private array $databases = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'dimeckr',
        'login' => 'dimeckr',
        'password' => '061102693DC'
    );

    static public function getLogin(): string
    {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['login'];
    }

    static public function getHostname(): string
    {
        return static::$databases['hostname'];
    }

    static public function getPassword(): string
    {
        return static::$databases['password'];
    }

    static public function getDatabase(): string
    {
        return static::$databases['database'];
    }

    static public function getAbsoluteURL(): string
    {
        return "http://localhost/web/index.php";
    }


}