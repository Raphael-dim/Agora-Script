<?php
namespace App\Vote\Config;
class Conf {

    static private array $databases = array(
        'hostname' => 'lucky.db.elephantsql.com',
        'database' => 'yhyayoup',
        'login' => 'yhyayoup',
        'password' => 'SfPcwfddmzZO-CBXuWYpUqg4Q6-IdS_j'
    );

    static public function getLogin() : string {
        // L'attribut statique $databases s'obtient avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['login'];
    }

    static public function getHostname() : string{
        return static::$databases['hostname'];
    }

    static public function getPassword() : string{
        return static::$databases['password'];
    }

    static public function getDatabase() : string{
        return static::$databases['database'];
    }

}
?>