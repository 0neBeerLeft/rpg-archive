<?php
$page = 'forgot-username';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(isset($_POST['submit'])){

  $email		=	$_POST['email'];
  $captcha	  	=	$_POST['captcha'];
  
  #Gegevens laden
  $gegeven = mysql_fetch_assoc(mysql_query("SELECT `username`, `email`, `voornaam`, `achternaam` FROM `gebruikers`  WHERE `email`='".$email."'")); 
  
  #Als er wel een email is ingevoerd
  if(empty($email)){
    echo '<div class="red">'.$txt['alert_no_email'].'</div>';
  }
  #Klopt deze email wel?
  elseif($gegeven['email'] != $email){
    echo '<div class="red">'.$txt['alert_email_dont_exist'].'</div>';
  }
  elseif(($captcha) != $_SESSION['captcha_code']){
    echo '<div class="red">'.$txt['alert_guardcore_invalid'].'</div>';
  }
  else{
    ### Headers
    $headers = "From: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Return-pathSender: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "X-Sender: \"".GLOBALDEF_ADMINEMAIL."\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
	
	$page = 'forgot-username';
	#Goeie taal erbij laden voor de mail
	include_once('language/language-mail.php');
	
    #E-Mail wachtwoord
    mail($email,
    $txt['mail_forgot_username_title'],
    '<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <center>
      <table width="80%" border="0" cellspacing="0" cellpadding="0">
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
          <td align="left" valign="top" bgcolor="#D3E9F5">'.$txt['mail_forgot_username'].'</td>
        </tr>
        <tr>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksonder.gif" width="11" height="11"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/onderbalk.gif" height="11"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsonder.gif" width="11" height="11"></td>
        </tr>
      </table>
      &copy; '.GLOBALDEF_SITENAME.' - '.date('Y').'
    </center>
    </body>
      </html>',
    $headers
    ); 
    
    echo '<div class="green">'.$txt['success_forgot_username'].'</div>';
  }
}

?>
<form method="post">
<center><p><?php echo $txt['title_text']; ?></p></center>
<center><table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" align="left" valign="top"><br /><br />
      <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150" height="37"><?php echo $txt['email']; ?></td>
          <td width="150"><input type="text" name="email" class="text_long" id="text" value="<?php if(isset($_POST ['email']) && !empty($_POST ['email'])) { echo $_POST ['email']; }?>" /></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td height="37"><img src="includes/captcha.php" alt="<?php echo $txt['captcha']; ?>" title="<?php echo $txt['captcha']; ?>" /></td>
        </tr>
        <tr>
          <td><? echo $txt['guardcode']; ?></td>
          <td height="37"><input name="captcha" type="text" class="text_long" maxlength="3"/></td>
        </tr>
        <tr>
          <td align="right" valign="bottom">&nbsp;</td>
          <td><button type="submit" name="submit" class="button"><?php echo $txt['button']; ?></button></td>
        </tr>
      </table>
      </td>
    <td width="300" height="240" style="background:url('images/infernape.png') no-repeat right top;"></td>
  </tr>
</table>
</center>
</form>