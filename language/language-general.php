<?php

$language_array = array("nl","en");

if(in_array(GLOBALDEF_LANGUAGE, $language_array)){

    #Load language
    require_once('general/language-general-'.GLOBALDEF_LANGUAGE.'.php');
} else {

    #Load language
    require_once('general/language-general-en.php');
}