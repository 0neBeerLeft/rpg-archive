<?php
session_start();
chdir('../');
include_once('includes/config.php');
include_once('includes/ingame.inc.php');

if ( isset($_POST['content']) and isset($_SESSION['naam']) )
{
    $dirty_html_tekst = $_POST['content'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
    
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $tekst = $purifier->purify($dirty_html_tekst);
    $replaceWith = array(
        "&lt;3" => "<img src='../images/shoutbox_icons/emoticon_heart.png' alt='heart' title='heart' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(y)" => "<img src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(Y)" => "<img src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(V)" => "<img src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(v)" => "<img src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":=)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":=]" => "<img src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "=]" => "<img src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-d" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":d" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-D" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":D" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "x-d" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "xd" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "x-D" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "xD" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":(" => "<img src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-(" => "<img src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "8)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "8-)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "B)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "B|" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(H)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "(h)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":o" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-o" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":O" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-O" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":P" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-P" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":p" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":-p" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "3)" => "<img src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "3-)" => "<img src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ";)" => "<img src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ";-)" => "<img src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":@" => "<img src='../images/shoutbox_icons/emoticon_angry.png' alt='angry' title='angry' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        ":'(" => "<img src='../images/shoutbox_icons/emoticon_crying.png' alt='crying' title='crying' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "[gold]" => "<img src='../images/shoutbox_icons/emoticon_gold.png' alt='gold' title='gold' style='border:none;height:16px;width:16px;vertical-align: middle;' />",
        "[silver]" => "<img src='../images/shoutbox_icons/emoticon_silver.png' alt='silver' title='silver' style='border:none;height:16px;width:16px;vertical-align: middle;' />"
    );
    $tekst = strtr($tekst,$replaceWith);

    $sendMessage = "INSERT INTO shoutbox (username,content,post_time,clan)
                    VALUES(:naam,:tekst,NOW(),NULL)";
    $stmt = $db->prepare($sendMessage);
    $stmt->bindParam(':tekst', $tekst, PDO::PARAM_STR);
    $stmt->bindParam(':naam', $_SESSION['naam'], PDO::PARAM_STR);
    $stmt->execute();

    if(file_exists('cache/shoutbox.txt')) {
        unlink('cache/shoutbox.txt');
    }
    exit();
}

?>