<?php

$language_array = array("nl","en");

if(in_array(GLOBALDEF_LANGUAGE, $language_array)){

    #Load language
    include('mail/language-mail-'.GLOBALDEF_LANGUAGE.'.php');
} else {

    #Load language
    include('mail/language-mail-en.php');
}
