<?php

$language_array = array("nl","en");

if(in_array(GLOBALDEF_LANGUAGE, $language_array)){

    #Load language
    include('box/language-box-'.GLOBALDEF_LANGUAGE.'.php');
} else {

    #Load language
    include('box/language-box-en.php');
}

