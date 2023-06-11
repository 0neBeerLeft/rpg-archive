<?
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if ($gebruiker['admin'] < 2) header('location: index.php?page=home');

#####################################################################
function send_mail($voornaam, $achternaam, $email, $packnaam, $packdagen, $packkosten) {
    ### Headers
    $headers = "From: " . GLOBALDEF_ADMINEMAIL . "\r\n";
    $headers .= "Return-pathSender: " . GLOBALDEF_ADMINEMAIL . "\r\n";
    $headers .= "X-Sender: \"" . GLOBALDEF_ADMINEMAIL . "\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: " . GLOBALDEF_ADMINEMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

    //Goeie taal erbij laden voor de mail
    include('language/language-mail.php');

    //Mail versturen
    mail($email,
        $txt['mail_premiumshop_title'],
        '<html>
				<head>
				  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>
				  <center>
					<table width="80%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksboven.gif" width="11" height="11"></td>
						<td height="11" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/bovenbalk.gif"></td>
						<td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsboven.gif" width="11" height="11"></td>
					  </tr>
					  
					  <tr>
						<td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksbalk.gif"></td>
						<td align="center" bgcolor="#D3E9F5"><img src="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/headermail.png" width="520" height="140"></td>
						<td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsbalk.gif"></td>
					  </tr>
					  <tr>
						<td align="left" valign="top" bgcolor="#D3E9F5">' . $txt['mail_premiumshop_password'] . '</td>
					  </tr>
					  <tr>
						<td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksonder.gif" width="11" height="11"></td>
						<td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/onderbalk.gif" height="11"></td>
						<td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsonder.gif" width="11" height="11"></td>
					  </tr>
					</table>
					&copy; '.GLOBALDEF_SITENAME.' - '.date('Y').'<br>
				  </center>
				</body>
			  </html>',
        $headers
    );
}

$userfield = $_GET['player'];
if (isset($_POST['give'])) {

    if (empty($_POST['username'])) {
        echo '<div class="red"><img src="images/icons/red.png"> Je hebt geen speelnaam ingevuld.</div>';
    } elseif (mysql_num_rows(mysql_query("SELECT user_id FROM gebruikers WHERE username = '" . $_POST['username'] . "'")) == 0) {
        echo '<div class="red"><img src="images/icons/red.png"> Speler bestaat niet.</div>';
    } else {
        $userfield = $_POST['username'];
        //laad informatie van de pack
        $user = mysql_fetch_array(mysql_query("SELECT `user_id`, `voornaam`, `achternaam`, `email` FROM gebruikers WHERE username = '" . $_POST['username'] . "'"));
        $pack = mysql_fetch_array(mysql_query("SELECT * FROM premium WHERE naam = '" . $_POST['pack'] . "'"));

        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'" . $pack['silver'] . "', `premiumaccount`=`premiumaccount`+'" . $pack['dagen'] . "', `gold`=`gold`+'" . $pack['gold'] . "' WHERE `username`='" . $_POST['username'] . "'");
        #Error tonen
        echo '<div class="green"><img src="images/icons/green.png"> Succesvol ' . $_POST['pack'] . ' aan ' . $_POST['username'] . ' gegeven.</div>';

        $event = 'Je hebt een ' . $_POST[pack] . ' van ' . $_SESSION[naam] . ' gekregen.';

        $result = $db->prepare("INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
							  VALUES (NOW(), :to, :event, '0')");
        $result->bindValue(':to', $user['user_id'], PDO::PARAM_INT);
        $result->bindValue(':event', $event, PDO::PARAM_STR);
        $result = $result->execute();


    }
}
?>

<center>
    <form method="post">
        <table width="300">
            <tr>
                <td>Trainers Name:</td>
                <td><input type="text" name="username" class="text_long" value="<?php echo $userfield; ?>"/></td>
            </tr>
            <tr>
                <td>Pack:</td>
                <td><select name="pack" class="text_select">
                        <?php $packsql = mysql_query("SELECT `naam` FROM `premium` ORDER BY `id`");
                        while ($info = mysql_fetch_array($packsql)) {
                            echo '<option value="' . $info['naam'] . '">' . $info['naam'] . '</option>';
                        } ?></select></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="give" value="Give Pack" class="button"/></td>
            </tr>
        </table>
    </form>
</center>