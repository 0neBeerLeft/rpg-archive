<?php
include_once ('cronConfig.php');

#Tijd uit de database halen, van de mensen die niet geactiveerd zjin
$sql = mysql_query("SELECT datum, user_id, username FROM `gebruikers` WHERE `account_code`!='1' AND `account_code`!='0'");
while ($gegeven = mysql_fetch_assoc($sql)) {
    #Als het meer dan een week geleden is. Dan verwijderen
    $tijdtoen = strtotime($gegeven['datum']);
    $tijdnu = strtotime(date('Y-m-d H:i:s')) - 604800;
    if ($tijdtoen < $tijdnu) {
        mysql_query("DELETE FROM `gebruikers` WHERE `user_id`='" . $gegeven['user_id'] . "'");
        mysql_query("DELETE FROM `gebruikers_item` WHERE `user_id`='" . $gegeven['user_id'] . "'");
        mysql_query("DELETE FROM `gebruikers_badges` WHERE `user_id`='" . $gegeven['user_id'] . "'");
        mysql_query("DELETE FROM `pokemon_speler` WHERE `user_id`='" . $gegeven['user_id'] . "'");
        mysql_query("DELETE FROM `pokemon_gezien` WHERE `user_id`='" . $gegeven['user_id'] . "'");
    }
}

#Jarigen een mail sturen
$datenow = date('m-d');
$birthdaysql = mysql_query("SELECT `username`, `email`, `land` FROM `gebruikers` WHERE `geb_datum` LIKE '%" . $datenow . "' AND `account_code`='1'");
$i = 0;
while ($birthday = mysql_fetch_assoc($birthdaysql)) {

    if (($birthday['land'] == 'Netherlands') || ($birthday['land'] == 'Belgium'))
        $birthday_message = 'Hartelijk gefeliciteerd ' . $birthday['username'] . '!<br /><br />
	We hebben een cadeau voor jou: <strong><img src="images/icons/silver.png"> 10.000!</strong><br />
	Kom zo snel mogelijk online om je silver te bekijken.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Een fijne dag gewenst!<br /><br />
	pokeworld Team';
    elseif ($birthday['land'] == 'Germany')
        $birthday_message = 'Happy birthday ' . $birthday['username'] . '!<br /><br />
	We have a present for you. <strong><img src="http://www.pokeworld.nl/images/icons/silver.png"> 10.000!</strong><br />
	Come as fast as possible online to see your huge amount of silver.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day!<br /><br />
	pokeworld Team';
    elseif ($birthday['land'] == 'Spain')
        $birthday_message = 'Happy birthday ' . $birthday['username'] . '!<br /><br />
	We have a present for you. <strong><img src="http://www.pokeworld.nl/images/icons/silver.png"> 10.000!</strong><br />
	Come as fast as possible online to see your huge amount of silver.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day!<br /><br />
	pokeworld Team';
    elseif ($birthday['land'] == 'Poland')
        $birthday_message = 'Happy birthday ' . $birthday['username'] . '!<br /><br />
	We have a present for you. <strong><img src="http://www.pokeworld.nl/images/icons/silver.png"> 10.000!</strong><br />
	Come as fast as possible online to see your huge amount of silver.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day!<br /><br />
	pokeworld Team';
    else
        $birthday_message = 'Happy birthday ' . $birthday['username'] . '!<br /><br />
	We have a present for you. <strong><img src="http://www.pokeworld.nl/images/icons/silver.png"> 10.000!</strong><br />
	Come as fast as possible online to see your huge amount of silver.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day!<br /><br />
	pokeworld Team';

    ### Headers.
    $headers = "From: info@pokeworld.nl\r\n";
    $headers .= "Return-pathSender: info@pokeworld.nl\r\n";
    $headers .= "X-Sender: \"info@pokeworld.nl\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: pokeworld.nl\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

    #Mail versturen
    mail($birthday['email'],
        'Happy birthday',
        '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<center>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td background="http://www.pokeworld.nl/images/mail/linksboven.gif" width="11" height="11"></td>
  <td height="11" background="http://www.pokeworld.nl/images/mail/bovenbalk.gif"></td>
  <td background="http://www.pokeworld.nl/images/mail/rechtsboven.gif" width="11" height="11"></td>
</tr>
<tr>
  <td width="11" rowspan="2" background="http://www.pokeworld.nl/images/mail/linksbalk.gif"></td>
  <td align="center" bgcolor="#D3E9F5"><img src="http://www.pokeworld.nl/images/mail/headermail.png" width="520" height="140"></td>
  <td width="11" rowspan="2" background="http://www.pokeworld.nl/images/mail/rechtsbalk.gif"></td>
</tr>
<tr>
  <td valign="top" bgcolor="#D3E9F5">' . $birthday_message . '</td>
</tr>
<tr>
  <td background="http://www.pokeworld.nl/images/mail/linksonder.gif" width="11" height="11"></td>
  <td background="http://www.pokeworld.nl/images/mail/onderbalk.gif" height="11"></td>
  <td background="http://www.pokeworld.nl/images/mail/rechtsonder.gif" width="11" height="11"></td>
</tr>
</table>
&copy; pokeworld ' . date('Y') . '
</center>
</body>
  </html>', $headers);
}

#Pokemon missen je
$time_1 = time() - 600000;
$time_2 = time() - 700000;

$misssql = mysql_query("SELECT `username`, `email`, `land` FROM `gebruikers` WHERE (`online` BETWEEN " . $time_1 . " AND " . $time_2 . ") AND `account_code`='1'");
while ($miss = mysql_fetch_assoc($misssql)) {

    if (($miss['land'] == 'Netherlands') || ($miss['land'] == 'Belgium'))
        $miss_message = 'Hallo ' . $miss['username'] . '!<br /><br />
	Je Pok&eacute;mon missen je enorm.<br />
	Kom je weer online om ze te trainen?<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Nog een prettige dag,<br /><br />
	PokeWorld.nl';
    elseif ($miss['land'] == 'Germany')
        $miss_message = 'Hello ' . $miss['username'] . '!<br /><br />
	Your pokemon miss you.<br />
	Come quick back online to train them.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day,<br /><br />
	pokeworld Team';
    elseif ($miss['land'] == 'Spain')
        $miss_message = 'Hello ' . $miss['username'] . '!<br /><br />
	Your pokemon miss you.<br />
	Come quick back online to train them.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day,<br /><br />
	pokeworld Team';
    elseif ($miss['land'] == 'Poland')
        $miss_message = 'Hello ' . $miss['username'] . '!<br /><br />
	Your pokemon miss you.<br />
	Come quick back online to train them.<br />
	<a href="http://www.pokeworld.nl">www.pokeworld.nl</a><br /><br />
	Have a nice day,<br /><br />
	pokeworld Team';
    else
        $miss_message = 'Hello ' . $miss['username'] . '!<br /><br />
	Your pokemon miss you.<br />
	Come quick back online to train them.<br />
	<a href="index.php">PokeWorld</a><br /><br />
	Have a nice day,<br /><br />
	pokeworld Team';

    ### Headers.
    $headers = "From: info@pokeworld.nl\r\n";
    $headers .= "Return-pathSender: info@pokeworld.nl\r\n";
    $headers .= "X-Sender: \"info@pokeworld.nl\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: info@pokeworld.nl\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

    #Mail versturen
    mail($miss['email'],
        'Important message',
        '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<center>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td background="images/mail/linksboven.gif" width="11" height="11"></td>
  <td height="11" background="images/mail/bovenbalk.gif"></td>
  <td background="images/mail/rechtsboven.gif" width="11" height="11"></td>
</tr>
<tr>
  <td width="11" rowspan="2" background="http://www.pokeworld.nl/images/mail/linksbalk.gif"></td>
  <td align="center" bgcolor="#D3E9F5"><img src="http://www.pokeworld.nl/images/mail/headermail.png" width="520" height="140"></td>
  <td width="11" rowspan="2" background="http://www.pokeworld.nl/images/mail/rechtsbalk.gif"></td>
</tr>
<tr>
  <td valign="top" bgcolor="#D3E9F5">' . $miss_message . '</td>
</tr>
<tr>
  <td background="images/mail/linksonder.gif" width="11" height="11"></td>
  <td background="images/mail/onderbalk.gif" height="11"></td>
  <td background="images/mail/rechtsonder.gif" width="11" height="11"></td>
</tr>
</table>
&copy; pokeworld ' . date('Y') . '
</center>
</body>
  </html>', $headers);
}

#Geef wat silver aan een jarige
mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'10000' WHERE `geb_datum`='".$datenow."' AND `account_code`='1'");

#Wel premium setting
mysql_query("UPDATE `gebruikers` SET `premiumaccount`=`premiumaccount`-'1', `stelen`='3', `geluksrad`='3', `storten`='5', `races`='10' WHERE `premiumaccount` > '0'");

#Niet premium settings
mysql_query("UPDATE `gebruikers` SET `stelen`='1', `geluksrad`='1', `storten`='3', `races`='5' WHERE `premiumaccount`='0'");

#Area Dragon weer ff naar ander gebied + regio transporteren
$gebiedrand = date('w');
$wereldrand = rand(1, 5);

if ($wereldrand == 1) $wereld = 'Kanto';
elseif ($wereldrand == 2) $wereld = 'Johto';
elseif ($wereldrand == 3) $wereld = 'Hoenn';
elseif ($wereldrand == 4) $wereld = 'Sinnoh';
elseif ($wereldrand == 5) $wereld = 'Unova';
elseif ($wereldrand == 6) $wereld = 'Kalos';

if ($gebiedrand == 0) $gebied = 'Lavagrot';
elseif ($gebiedrand == 1) $gebied = 'Grot';
elseif ($gebiedrand == 2) $gebied = 'Gras';
elseif ($gebiedrand == 3) $gebied = 'Spookhuis';
elseif ($gebiedrand == 4) $gebied = 'Vechtschool';
elseif ($gebiedrand == 5) $gebied = 'Strand';
elseif ($gebiedrand == 6) $gebied = 'Water';

mysql_query("UPDATE pokemon_wild SET wereld = '" . $wereld . "', gebied = '" . $gebied . "' WHERE wild_id = '650'");

#3 dagen oud Race legen
$time = time() - 259200;
mysql_query("DELETE FROM races WHERE time <'" . $time . "'");

#Pokemons langer dan 5 dagen op transferlijst weg.
$old_date = date("Y-m-d", time() - 432000);
$trans_old_sql = mysql_query("SELECT id, pokemon_id FROM transferlijst WHERE datum='" . $old_date . "'");
while ($trans_old = mysql_fetch_assoc($trans_old_sql)) {
    mysql_query("UPDATE `pokemon_speler` SET `opzak`='nee' WHERE `id`='" . $trans_old['pokemon_id'] . "'");
}
mysql_query("DELETE FROM `transferlijst` WHERE datum='" . $old_date . "'");

#Traders veranderen
mysql_query("UPDATE traders SET wil = ''");

#Markt eitjes voorraad wijzigen op Maandag en Donderdag
if ((date('w') == 1) OR (date('w') == 4)) mysql_query("UPDATE markt SET beschikbaar = '0' WHERE soort = 'pokemon'");

#Tijd opslaan van wanneer deze file is uitevoerd
mysql_query("UPDATE `cron` SET `tijd`='" . date("Y-m-d H:i:s") . "' WHERE `soort`='dag'");

#Tabellen optimaliseren.
mysql_query("OPTIMIZE TABLE `gebruikers`");
mysql_query("OPTIMIZE TABLE `gebruikers_item`");
mysql_query("OPTIMIZE TABLE `pokemon_speler`");
mysql_query("OPTIMIZE TABLE `pokemon_speler_gevecht`");
mysql_query("OPTIMIZE TABLE `pokemon_wild`");
mysql_query("OPTIMIZE TABLE `pokemon_wild_gevecht`");
mysql_query("OPTIMIZE TABLE `aanval_log`");
mysql_query("OPTIMIZE TABLE `cron`");
mysql_query("OPTIMIZE TABLE `inlog_fout`");
mysql_query("OPTIMIZE TABLE `inlog_logs`");
mysql_query("OPTIMIZE TABLE `pokemon_gezien`");
mysql_query("OPTIMIZE TABLE `pokemon_nieuw_baby`");
mysql_query("OPTIMIZE TABLE `pokemon_nieuw_starter`");
mysql_query("OPTIMIZE TABLE `pokemon_nieuw_gewoon`");

$profiles1 = mysql_query("SELECT user_id,username,fishing FROM `gebruikers` ORDER BY `fishing` DESC LIMIT 3");
while ($profiles = mysql_fetch_object($profiles1)) {
    $i++;
    if ($i == 1) {
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'2000' WHERE `username`='" . $profiles->username . "' AND `account_code`='1'");
        mysql_query("UPDATE `server` SET `fish`='" . $profiles->user_id . "'  WHERE `id`='1'");
        #Bericht opstellen na wat de language van de user is
        $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" />Je bent 1e in het visserij- toernooi en wint 2000<img src="images/icons/silver.png">.';

        #Melding geven aan de uitdager
        mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
					VALUES (NULL, NOW(), '" . $profiles->user_id . "', '" . $event . "', '0')");
    }
    if ($i == 2) {
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'1500' WHERE `username`='" . $profiles->username . "' AND `account_code`='1'");
        mysql_query("UPDATE `server` SET `fish2`='" . $profiles->user_id . "'  WHERE `id`='1'");
        #Bericht opstellen na wat de language van de user is
        $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" />Je bent 2e in het visserij- toernooi en wint 1500<img src="images/icons/silver.png">.';

        #Melding geven aan de uitdager
        mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
					VALUES (NULL, NOW(), '" . $profiles->user_id . "', '" . $event . "', '0')");
    }
    if ($i == 3) {
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'1000' WHERE `username`='" . $profiles->username . "' AND `account_code`='1'");
        mysql_query("UPDATE `server` SET `fish3`='" . $profiles->user_id . "'  WHERE `id`='1'");
        #Bericht opstellen na wat de language van de user is
        $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" />Je bent 3e in het visserij- toernooi en wint 1000<img src="images/icons/silver.png">.';

        #Melding geven aan de uitdager
        mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
					VALUES (NULL, NOW(), '" . $profiles->user_id . "', '" . $event . "', '0')");
    }
}

//account relation actions
$accounts1 = mysql_query("SELECT UNIX_TIMESTAMP(`reclameAanSinds`) AS `reclameAanSinds`,`online`,reclame,premiumaccount,respect_add,user_id,username FROM `gebruikers`");
while ($accounts = mysql_fetch_assoc($accounts1)) {
    //fishingpoints geven
    if ($accounts['premiumaccount'] > 0) {
        mysql_query("UPDATE `gebruikers` SET `fish`='6' WHERE `user_id`='" . $accounts['user_id'] . "' AND `account_code`='1'");
    } else {
        mysql_query("UPDATE `gebruikers` SET `fish`='3' WHERE `user_id`='" . $accounts['user_id'] . "' AND `account_code`='1'");
    }
    //reclame gold geven
    if ($accounts['reclame'] == 1 AND (24 * 3600) + $accounts['reclameAanSinds'] <= time()) {
        if((120 * 3600) + $accounts['online'] >= time()) {
            mysql_query("UPDATE `gebruikers` SET `gold`=`gold`+5 WHERE `user_id`='" . $accounts['user_id'] . "'");
        }
    }
    //respectpunt geven
    mysql_query("UPDATE `gebruikers` SET `respect_add`=`respect_add`+1 WHERE `user_id`='" . $accounts['user_id'] . "'");
}
mysql_query("UPDATE `gebruikers` SET `fishing`='0' WHERE `fishing`>'0' AND `account_code`='1'");

?>
