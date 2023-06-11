<?
#Filter Pack
function add_premium($username,$packnaam){
  #laad informatie van de pack
  $pack = mysql_fetch_assoc(mysql_query("SELECT `dagen`, `kosten`, `silver`, `gold` FROM `premium` WHERE `naam`='".$packnaam."'"));
  
  #Update de gegevens bij de gebruiker
  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$pack['silver']."', `gold`=`gold`+'".$pack['gold']."', `premiumaccount`=`premiumaccount`+'".$pack['dagen']."' WHERE `username`='".$username."'");
  
  #Laad mail van de gebruiker
  $user = mysql_fetch_assoc(mysql_query("SELECT `username`, `voornaam`, `achternaam`, `email` FROM `gebruikers` WHERE `username`='".$username."'"));
  #Stuur mail
  if(!empty($user['voornaam'])){
        $voornaam = $user['voornaam'];
        $achternaam = $user['achternaam'];
  }else{
        $voornaam = $user['username'];
        $achternaam = "";
  }
  
  send_mail($voornaam,$achternaam,$user['email'],$packnaam,$pack['dagen'],$pack['kosten']);
}
  
#Mail sturen
function send_mail($voornaam,$achternaam,$email,$packnaam,$packdagen,$packkosten){
    ### Headers
    $headers = "From: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Return-pathSender: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "X-Sender: \"".GLOBALDEF_ADMINEMAIL."\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

    $subject = GLOBALDEF_SITENAME." premium aankoop";

	#Goeie taal erbij laden voor de mail
	include('../../language/language-mail.php');
		
    #Mail versturen
    mail($email,
        $subject,
      '
      <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <body>
          <center>
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/linksboven.gif" width="11" height="11"></td>
                <td height="11" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/bovenbalk.gif"></td>
                <td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/rechtsboven.gif" width="11" height="11"></td>
              </tr>
              
              <tr>
                <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/linksbalk.gif"></td>
                <td align="center" bgcolor="#D3E9F5"><img src="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/headermail.png" width="520"></td>
                <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/rechtsbalk.gif"></td>
              </tr>
              <tr>
                <td align="left" valign="top" bgcolor="#D3E9F5">Beste '.$voornaam.' '.$achternaam.',<br><br>
									Je hebt een <b>'.$packnaam.'</b> gekocht ter waarde van &euro;'.$packkosten.'<br>
									Bewaar deze e-mail goed, dit geldt als een aankoopbewijs.<br><br>
									Veel plezier met je aankoop!<br><br>
									Met vriendelijke groet,<br>
									'.GLOBALDEF_SITENAME.'</td>
              </tr>
              <tr>
                <td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/linksonder.gif" width="11" height="11"></td>
                <td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/onderbalk.gif" height="11"></td>
                <td background="'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_DOMAIN.'/images/mail/rechtsonder.gif" width="11" height="11"></td>
              </tr>
            </table>
            &copy; '.GLOBALDEF_SITENAME.' - '.date('Y').'<br/>
          </center>
        </body>
      </html>
      ',
      $headers
    );
  }
?>