<?php
$language_array = array("nl","en");

if(in_array(GLOBALDEF_LANGUAGE, $language_array)){

    #Load language
    $newsQuery = "SELECT text_".GLOBALDEF_LANGUAGE." FROM home";
    $stmt = $db->prepare($newsQuery);
    $stmt->execute();
    $home = $stmt->fetch(PDO::FETCH_ASSOC);

    echo (nl2br($home['text_'.GLOBALDEF_LANGUAGE]));

} else {
    $newsQuery = "SELECT text_en FROM home";
    $stmt = $db->prepare($newsQuery);
    $stmt->execute();
    $home = $stmt->fetch(PDO::FETCH_ASSOC);

    echo (nl2br($home['text_en']));

}