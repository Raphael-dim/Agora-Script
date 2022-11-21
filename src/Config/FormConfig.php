<?php

namespace App\Vote\Config;

class FormConfig
{

    /*
     * Si une variable session ou publiée existe pour le menu déroulant,
     * alors on selectionne la valeur concernée
     */
    static public function DropDown($param, $value)
    {
        if (isset($_POST[$param]) && $_POST[$param] == $value) {
            return " selected =\"selected\"";
        } else if (isset($_SESSION[$param]) && $_SESSION[$param] == $value) {
            return " selected =\"selected\"";
        }
    }

    /*
     * Si une variable session ou publiée existe pour le champ de texte,
     * alors on l'applique en tant que valeur
     */
    static public function TextField($param)
    {
        if (isset($_POST[$param])) {
            return $_POST[$param];
        } else if (isset($_SESSION[$param])) {
            return $_SESSION[$param];
        }
    }

    static public function initialiserSessions($question): void
    {
        $calendrier = $question->getCalendrier();
        $tabSections = $question->getSections();
        $_SESSION['Titre'] = $question->getTitre();
        $_SESSION['Description'] = $question->getDescription();
        $_SESSION['nbSections'] = count($question->getSections());
        $_SESSION['debutEcriture'] = $calendrier->getDebutEcriture();
        $_SESSION['finEcriture'] = $calendrier->getFinEcriture();
        $_SESSION['debutVote'] = $calendrier->getDebutVote();
        $_SESSION['finVote'] = $calendrier->getFinVote();
        for ($i = 1; $i <= count($tabSections); $i++) {
            $_SESSION['titre' . $i] = $tabSections[$i - 1]->getTitre();
            $_SESSION['description' . $i] = $tabSections[$i - 1]->getDescription();
        }
        $responsables = $question->getResponsables();
        $_SESSION['responsables'] = array();
        foreach ($responsables as $responsable) {
            $_SESSION['responsables'][] = $responsable->getIdentifiant();
        }
        $votants = $question->getVotants();
        $_SESSION['votants'] = array();
        foreach ($votants as $votant) {
            $_SESSION['votants'][] = $votant->getIdentifiant();
        }
    }

    /*
     * Redirige l'utilisateur vers l'url
     */
    static public function redirect($url = null)
    {
        if ($url != null) {
            header("location: {$url}");
            exit;
        }
    }

    /*
     * Enregistre les champs du form en tant que variable de session
     */
    static public function postSession()
    {
        $keys = array();

        /*
         * On crée une variable de session pour chaque valeur publiée
         */
        foreach ($_POST as $key => $value) {
            $value = is_array($value) ? $value : trim($value);
            $_SESSION[$key] = $value;
        }
    }

    public static function printSession()
    {
        var_dump($_SESSION);
    }

    public static function testDates($Adates)
    {

    }
}