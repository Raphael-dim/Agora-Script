<?php

namespace App\Vote\Config;

class FormConfig
{
    static public function DropDown($param,$value){
        if (isset($_POST[$param]) && $_POST[$param] == $value){
            return " selected =\"selected\"";
        }else if (isset($_SESSION[$param]) && $_SESSION[$param] == $value){
            return " selected =\"selected\"";
        }
    }

    static public function TextField($param){
        if (isset($_POST[$param])){
            return $_POST[$param];
        }
        else if (isset($_SESSION[$param])){
            return $_SESSION[$param];
        }
    }

    static public function redirect($url=null){
        if ($url !=null){
            header("location: {$url}");
            exit;
        }
    }

    static public function postSession($step = array()){
        $keys = array();

        foreach($_POST as $key => $value){
            $value = is_array($value) ? $value : trim($value);
            $Akey = explode("#",$key);
            if(in_array($Akey[0],$step)){
                $keys[$key] = $value;
            } else{
                $_SESSION[$key] = $value;
            }

        }

        if(!empty($keys)){

            foreach($_SESSION as $key => $value){
                $Akey = explode("#",$key);
                if(in_array($Akey[0],$keys) && !array_key_exists($key,$keys)){
                    unset($_SESSION[$key]);
                }
            }
            foreach($Akey as $key => $value){
                $_SESSION[$key] = $value;
        }
        } else{

            foreach($_SESSION as $key =>$value){
                $Akey = explode("#",$key);
                if(in_array($Akey[0],$keys)){
                    unset($_SESSION[$key]);
                }
            }
        }

    }

    public static function printSession(){
        var_dump($_SESSION);
    }

    public static function testDates($Adates){

    }
}