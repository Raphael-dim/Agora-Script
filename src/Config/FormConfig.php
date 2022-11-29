<?php

namespace App\Vote\Config;

use App\Vote\Model\HTTP\Session;

class FormConfig
{
    public static String $arr = '';


    static public function startSession(){
        Session::getInstance();
        if (isset($_SESSION[FormConfig::$arr])){
            unset($_SESSION[FormConfig::$arr]);
        }
        $_SESSION[FormConfig::$arr] = array();
    }

    static public function setArr(String $string){
        FormConfig::$arr=$string;
    }
    /*
     * Si une variable session ou publiée existe pour le menu déroulant,
     * alors on selectionne la valeur concernée
     */
    static public function DropDown($param, $value)
    {
        if (isset($_POST[$param]) && $_POST[$param] == $value) {
            return " selected =\"selected\"";
        } else if (isset($_SESSION[FormConfig::$arr][$param]) && $_SESSION[FormConfig::$arr][$param] == $value) {
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
        } else if (isset($_SESSION[FormConfig::$arr][$param])) {
            return $_SESSION[FormConfig::$arr][$param];
        }
    }

    static public function initialiserSessions($question): void
    {
        $calendrier = $question->getCalendrier();
        $tabSections = $question->getSections();
        $_SESSION[FormConfig::$arr]['Titre'] = $question->getTitre();
        $_SESSION[FormConfig::$arr]['Description'] = $question->getDescription();
        $_SESSION[FormConfig::$arr]['nbSections'] = count($question->getSections());
        $_SESSION[FormConfig::$arr]['debutEcriture'] = $calendrier->getDebutEcriture();
        $_SESSION[FormConfig::$arr]['finEcriture'] = $calendrier->getFinEcriture();
        $_SESSION[FormConfig::$arr]['debutVote'] = $calendrier->getDebutVote();
        $_SESSION[FormConfig::$arr]['finVote'] = $calendrier->getFinVote();
        for ($i = 1; $i <= count($tabSections); $i++) {
            $_SESSION[FormConfig::$arr]['titre' . $i] = $tabSections[$i - 1]->getTitre();
            $_SESSION[FormConfig::$arr]['description' . $i] = $tabSections[$i - 1]->getDescription();
        }

        $responsables = $question->getResponsables();
        $_SESSION[FormConfig::$arr]['responsables'] = array();
        $_SESSION[FormConfig::$arr]['co-auteurs'] = array();
        foreach ($responsables as $responsable) {
            $_SESSION[FormConfig::$arr]['responsables'][] = $responsable->getIdentifiant();
        }
        $votants = $question->getVotants();
        $_SESSION[FormConfig::$arr]['votants'] = array();
        foreach ($votants as $votant) {
            $_SESSION[FormConfig::$arr]['votants'][] = $votant->getIdentifiant();
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
            $_SESSION[FormConfig::$arr][$key] = $value;
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