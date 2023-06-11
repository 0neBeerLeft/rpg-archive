<?php
$page = 'forgot-password';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(isset($_POST['submit'])){

  $inlognaam	=	$_POST['inlognaam'];
  $email		=	$_POST['email'];
  $captcha	  	=	$_POST['captcha'];
  
  #Gegevens laden
  $gegeven = mysql_fetch_assoc(mysql_query("SELECT `user_id`, `username`, `email`, `voornaam`, `achternaam` FROM `gebruikers` WHERE `username`='".$inlognaam."' OR `email`='".$email."'")); 
    
  #Als er geen inlognaam is ingevoerd
  if(empty($inlognaam)){
    echo '<div class="red">'.$txt['alert_no_username'].'</div>';
  }
  #Als inlognaam korter is dan 3 karakters
  elseif(strlen($inlognaam) < 3){
    echo '<div class="red">'.$txt['alert_username_too_short'].'</div>';
  }
  #Als de inlognaam langer is dan 10 karakters
  elseif(strlen($inlognaam) > 10){
    echo '<div class="red">'.$txt['alert_username_too_long'].'.</div>';
  }
  #Als er wel een email is ingevoerd
  elseif(empty($email)){
    echo '<div class="red">'.$txt['alert_no_email'].'</div>';
  }
  elseif(($captcha) != $_SESSION['captcha_code']){
    echo '<div class="red">'.$txt['alert_guardcore_invalid'].'</div>';
  }
  #Bestaat deze gebruiker wel?
  elseif($gegeven['username'] != $inlognaam){
    echo '<div class="red">'.$txt['alert_username_dont_exist'].'</div>';
  }
  #Klopt deze email wel?
  elseif($gegeven['email'] != $email){
    echo '<div class="red">'.$txt['alert_email_dont_exist'].'</div>';
  }
  elseif($gegeven['user_id'] == ''){
    echo '<div class="red">'.$txt['alert_wrong_conbination'].'</div>';
  }
  else{
	 #Wachtwoord lengte, moet deelbaar door 2 zijn. Dus, 2-4-6-8 enz.
    $length       =    10; 

    # Password generation 
    $conso=array("b","c","d","f","g","h","j","k","l", 
    "m","n","p","r","s","t","v","w","x","y","z"); 
    $vocal=array("a","e","i","o","u"); 
    $numbers=array("1","2","3","4","5","6","7","8","9","0");
    $nieuwww=""; 
    srand ((double)microtime()*1000000); 
    $max = $length/2; 
    for($i=1; $i<=$max; $i++) 
    { 
      #Wachtwoord
      $nieuwww.=$conso[rand(0,19)]; 
      $nieuwww.=$vocal[rand(0,4)]; 
    } 
    
    #Md5 versie van het wachtwoord
    $nieuwwwmd5 = md5($nieuwww); 

    ### Headers
    $headers = "From: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Return-pathSender: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "X-Sender: \"".GLOBALDEF_ADMINEMAIL."\" \n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "Bcc: ".GLOBALDEF_ADMINEMAIL."\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
	
	$page = 'forgot-password';
	#Goeie taal erbij laden voor de mail
	include_once('language/language-mail.php');
	
    #E-Mail wachtwoord
    mail($email,
    $txt['mail_forgot_password_title'],
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
          <td align="left" valign="top" bgcolor="#D3E9F5">'.$txt['mail_forgot_password'].'</td>
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
    
    #invoegen in wwvergeten tabel
    mysql_query("INSERT INTO `wwvergeten` (`naam`, `ip`, `email`) 
    VALUES ('".$inlognaam."', '".$_SERVER['REMOTE_ADDR']."', '".$email."')");
    #In de database zetten
    mysql_query("UPDATE `gebruikers` SET `wachtwoord`='".$nieuwwwmd5."' WHERE `username`='".$gegeven['username']."' AND `email`='".$gegeven['email']."'");

    echo '<div class="green">'.$txt['success_forgot_password'].'</div>';
  }
}

?>
<form method="post">
<center><p><?php echo $txt['title_text']; ?></p></center>
<center><table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" align="left" valign="top"><br />
      <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150" height="37" valign="middle"><? echo $txt['username']; ?></td>
          <td width="150"><input type="text" name="inlognaam" class="text_long" value="<?php if(isset($_POST ['inlognaam']) && !empty($_POST ['inlognaam'])) { echo $_POST ['inlognaam']; }?>" maxlength="10" /></td>
        </tr>
        <tr>
          <td height="37"><?php echo $txt['email']; ?></td>
          <td><input type="text" name="email" class="text_long" id="text" value="<?php if(isset($_POST ['email']) && !empty($_POST ['email'])) { echo $_POST ['email']; }?>" /></td>
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
    <td width="300" style="background:url('images/electivire.png') no-repeat right top;"></td>
  </tr>
</table>
</center>
</form>