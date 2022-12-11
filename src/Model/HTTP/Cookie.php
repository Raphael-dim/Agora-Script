<?php

namespace App\Vote\Model\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, $valeur, $dureeExpiration = null): void
    {
        if ($dureeExpiration == null) {
            setcookie($cle, serialize($valeur));
        } else {
            setcookie($cle, serialize($valeur), time() + $dureeExpiration);
        }
    }

    public static function lire(string $cle)
    {
        return unserialize($_COOKIE[$cle]);
    }

    public static function contient($cle): bool
    {
        if (isset($_COOKIE[$cle])) return true;
        return false;
    }

    public static function supprimer($cle): void
    {
        if (self::contient($cle)) {
            unset($_COOKIE[$cle]);
            setcookie($cle, "", 1);

        }
    }
}
