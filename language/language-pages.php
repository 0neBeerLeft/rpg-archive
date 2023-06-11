<?php

$language_array = array("nl","en");

if(in_array(GLOBALDEF_LANGUAGE, $language_array)){

    #Load language
    include('pages/language-pages-'.GLOBALDEF_LANGUAGE.'.php');
} else {

    #Load language
    include('pages/language-pages-en.php');
}