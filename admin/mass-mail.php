<?
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if ($gebruiker['admin'] < 3) header('location: index.php?page=home');


//Als er op de verstuur knop gedrukt word
if (isset($_POST['verstuur'])) {

    //Makkelijk naam toewijzen
    $bericht = $_POST['tekst'];
    $onderwerp = $_POST['onderwerp'];
    //Als er geen bericht is ingetypt
    if (empty($bericht)) {
        echo '<div class="red">Geen tekst ingevuld.</div>';
    } //Als alles is ingevuld het bericht versturen
    else {
        $speler = mysql_query("SELECT `username`, `email` FROM `gebruikers`"); #between '1900' and '4000'
        $aantal = 0;
        while ($spelers = mysql_fetch_array($speler)) {
            $aantal++;
            //Als er geen onderwerp is ingevuld een onderwerp toewijzen
            if ($onderwerp == '' || $onderwerp == 'Onderwerp') {
                $onderwerp = GLOBALDEF_SITENAME." Mail";
            }
            //In de database zetten
            //Tijd opvragen.
            $datum = date('Y-m-d H:i:s');
            $verstuurd = date('d-m-y H:i');
            //Spaties weghalen
            ### Headers
            $headers = "From: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "Return-pathSender: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "X-Sender: \"" . GLOBALDEF_ADMINEMAIL . "\" \n";
            $headers .= "X-Mailer: PHP\n";
            $headers .= "Bcc: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

            //$bericht = nl2br($bericht);
            //Mail versturen
            mail($spelers['email'],
                $onderwerp,
                '	<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div align="center">
  <table width="70%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksboven.gif" width="11" height="11"></td>
      <td height="11" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/bovenbalk.gif"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsboven.gif" width="11" height="11"></td>
    </tr>

    <tr>
      <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksbalk.gif"></td>
      <td align="center" bgcolor="#D3E9F5"><img src="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/headermail.png" width="520" height="140"></td>
      <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsbalk.gif"></td>
    </tr>
    <tr>
      <td align="left" valign="top" bgcolor="#D3E9F5">Beste ' . $spelers['username'] . '!<br /><br />
        ' . nl2br($_POST['tekst']) . '
      </td>
    </tr>
    <tr>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksonder.gif" width="11" height="11"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/onderbalk.gif" height="11"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsonder.gif" width="11" height="11"></td>
    </tr>
  </table>
  &copy; '.GLOBALDEF_SITENAME.' - '.date('Y').'<br>
</div>
</body>
      </html>',
                $headers
            );

        }
        echo '<div class="green">Massamail succesvol ' . $aantal . 'x verstuurd!</div>';
    }

}

?>
<form method="post">
    <table width="660" cellpadding="0" cellspacing="0">
        <tr>
            <td width="110">Onderwerp:</td>
            <td width="550"><input type="text" name="onderwerp" class="text_long"
                                   value="<?php if ($_POST['onderwerp'] != '') echo $_POST['onderwerp']; ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><textarea style="width:580px;" class="text_area" rows="15"
                                      name="tekst"><?php if ($_POST['tekst'] != '') echo $_POST['tekst']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Send mail" name="verstuur" class="button"></td>
        </tr>
    </table>
</form>