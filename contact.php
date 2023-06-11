    <?php
	$page = 'contact';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');

	if(isset($_POST['verstuur'])){
		if(empty($_POST['naam'])){
			echo '<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_name'].'</div>';
		}
		elseif(empty($_POST['email'])){
			echo '<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_email'].'</div>';
		}
		elseif(!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $_POST['email'])){
			echo '<div class="red"><img src="images/icons/red.png"> '.$txt['alert_email_incorrect_signs'].'</div>';
		}
		elseif(empty($_POST['onderwerp'])){
			echo '<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_subject'].'</div>';
		}
		elseif(empty($_POST['bericht'])){
			echo '<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_message'].'</div>';
		}
		else{
			echo '<div class="green"><img src="images/icons/green.png"> '.$txt['success_contact'].'</div>';
			
			$bericht = nl2br($_POST['bericht']);
			$naam = $_POST['naam'];
			$email = $_POST['email'];

			### Headers
			$headers = "From: ".GLOBALDEF_ADMINEMAIL."\r\n";
			$headers .= "Return-pathSender: ".GLOBALDEF_ADMINEMAIL."\r\n";
			$headers .= "X-Sender: \"".GLOBALDEF_ADMINEMAIL."\" \n";
			$headers .= "X-Mailer: PHP\n";
			$headers .= "Bcc: ".GLOBALDEF_ADMINEMAIL."\r\n";
			$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
			
			$page = 'contact';
			//Goeie taal erbij laden voor de mail
			include_once('language/language-mail.php');
			
			//E-Mail
			mail($_POST['sendto'], $_POST['onderwerp'],
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
      <td valign="top" bgcolor="#D3E9F5">'.$naam.'<br/><br/>'.$email.'<br/><br/>'.$txt['mail_contact'].'</td>
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
		}
	}
	
	?>
    <p><?php echo $txt['title_text']; ?></p>
    <form method="post" action="<?php echo $PHP_SELF; ?>">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding:15px;">
    	<tr>
          	<td><label for="email"><?php echo $txt['your_name']; ?></label></td>
          	<td><input type="tekst" name="naam" class="text_long" value="<?php if(!empty($_POST['naam'])) echo $_POST['naam']; ?>" id="name" /></td>
        </tr>
    	<tr>
          	<td><label for="email"><?php echo $txt['your_email']; ?></label></td>
          	<td><input type="tekst" name="email" class="text_long" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>" id="email" /></td>
        </tr>
    	<tr>
          	<td><label for="subject"><?php echo $txt['subject']; ?></label></td>
          	<td><input type="tekst" name="onderwerp" class="text_long" value="<?php if(!empty($_POST['onderwerp'])) echo $_POST['onderwerp']; ?>" id="subject" /></td>
        </tr>
        <tr>
        	<td colspan="2"><label for="message"><strong><?php echo $txt['message']; ?></strong></label></td>
        </tr>
        <tr>
            <td colspan="2"><textarea style="width:100%;" name="bericht" class="text_area" rows="12" id="message" /><?php if(!empty($_POST['bericht'])) echo $_POST['bericht']; ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" name="verstuur" class="button"><?php echo $txt['button']; ?></button></td>
        </tr>
        <tr>
            <td colspan="2"><br/><br/><h3><?=GLOBALDEF_SITENAME?> <?=GLOBALDEF_SITEDISCLAIMER?></td>
        </tr>
     </table>
     </form>