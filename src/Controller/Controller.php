<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\UtilisateurRepository;

class Controller
{
    /**
     * Cette fonction affiche la vue spécifiée dans $cheminVue avec les variables contenues dans $parametres
     * @param string $cheminVue chemin de la vue à afficher
     * @param array $parametres les paramètres passés à la vue, tableau associatif où les clés sont les noms de variables
     * @return void
     */
    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        // Crée des variables à partir du tableau $parametres
        extract($parametres);
        // Inclut le fichier de vue
        require "../src/View/$cheminVue";
    }

    /**
     * Cette fonction effectue une redirection vers l'URL spécifiée
     * @param string $url  URL vers laquelle le client sera redirigé
     * @return void
     */
    public static function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}