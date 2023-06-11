<?php
include("includes/security.php");
$page = 'clan-make';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(($gebruiker['rank'] < 5) ) header('Location: index.php');

if(isset($_POST['make'])){
	
	#clan_id aanmaken
	$query ="SELECT max(clan_id) FROM clans";
	$result = mysql_query($query) or die ("Error in query: $query. " .mysql_error());
	$row = mysql_fetch_row($result);
	$row2 = $row[0];
	$row3 = 1 + $row2;
	
	$clan_id				= $row3;
	$clan_naam				= $_POST['clan_naam'];
	$clan_owner				= $_SESSION['naam'];
	$clan_ownerid			= $_SESSION['id'];
	$clan_spelersaantal		= 1;
	$clan_members			= (string)$_SESSION['id'];
	$clan_beschrijving		= $_POST['clan_beschrijving'];
	$clan_type				= $_POST['clan_type'];
	$clan_level				= 1;
	$captcha				= $_POST['captcha'];
	$goldneed				= 100;
	$clan_gold				= 0;
	$clan_silver			= 0;

	#clan_naam
	if(empty($clan_naam)){
		$foutje1		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Please enter a clan name</div>';
  	}
  	elseif(strlen($clan_naam) > 20 ){
		$foutje1		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Clan name can not be longer than 20 characters.</div>';
	}
	#Bestaat naam al
	elseif(mysql_num_rows(mysql_query("SELECT `clan_naam` FROM `clans` WHERE `clan_naam`='".$_POST['clan_naam']."'")) >= 1){
		$foutje5	   	= '<span class="error_red">*</span>';
		$alert 		   	= '<div class="red">Clan name is already in use.</div>';
  	}
    #clan_beschrijving
	elseif(empty($clan_beschrijving)){
		$foutje2		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Please fill in a description.</div>';
  	}
  	elseif(strlen($clan_beschrijving) > 50 ){
		$foutje1		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Description can not be longer than 100 characters.</div>';
	}
    #clan_type
	elseif(empty($clan_type)){
		$foutje3		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Please select a type.</div>';
  	}
    #Bestaat de gebruiker al.
	elseif(mysql_num_rows(mysql_query("SELECT `clan_owner` FROM `clans` WHERE `clan_owner`='".$_SESSION['naam']."'")) >= 1){
		$foutje5	   	= '<span class="error_red">*</span>';
		$alert 		   	= '<div class="red">You have already created a clan.</div>';
  	}
  	#Kijken als het geen speciale tekens bevat
  	elseif(!preg_match('/^([a-zA-Z0-9]+)$/is', $clan_naam)){
		$foutje5	   	= '<span class="error_red">*</span>';
		$alert 			= '<div class="red">Clan name can only contain letters and numbers.</div>';
  	}
	#clan_id
	elseif(mysql_num_rows(mysql_query("SELECT `clan_id` FROM `clans` WHERE `clan_id`='".$row3."'")) >= 1){
		$foutje5	   	= '<span class="error_red">*</span>';
		$alert 		   	= '<div class="red">Clan name is already in use.</div>';
  	}
  	#Is de captcha wel goed ingevoerd  
  	elseif(($captcha) != $_SESSION['captcha_code']){
		$foutje12		= '<span class="error_red">*</span>';
		$alert 			= '<div class="red">Captcha not entered correctly.</div>';
  	}
	#genoeg gold?
	elseif($gebruiker['gold'] < $goldneed) echo '<div class="red">You do not have enough gold.</div>';
	
	#Is Ip adress al in gebruik?
	#elseif(($check['ip_aangemeld'] == $ip) && ($countdown_time > 0))
	#	$alert 			= '<div class="red">'.$txt['alert_already_this_ip'].'</div>';
	
	
	
	
  else{ 
  	#Gebruiker in de database
  	mysql_query("INSERT INTO `clans` (`clan_id`,`clan_naam`, `clan_owner`, `clan_ownerid`, `clan_spelersaantal`, `clan_members`, `clan_beschrijving`, `clan_type`, `clan_level`, `clan_gold`, `clan_silver`) 
    VALUES ('".$clan_id."','".$clan_naam."', '".$clan_owner."', '".$clan_ownerid."', '".$clan_spelersaantal."', '".$clan_members."', '".$clan_beschrijving."', '".$clan_type."', '".$clan_level."', '".$clan_gold."', '".$clan_silver."')");
	#Geldafnemen
	mysql_query("UPDATE gebruikers SET gold = gold-'".$goldneed."' WHERE user_id = '".$_SESSION['id']."'");
	mysql_query("UPDATE gebruikers SET clan = '".$clan_naam."' WHERE user_id = '".$_SESSION['id']."'");
		  
		  #Bericht opstellen
  echo '<div class="green"><img src="images/icons/green.png"> '.$_POST['clan_naam'].' created successfully.</div>';
  
  }
}
?>

<form method="post">
<center>
<?php if($alert != '') echo $alert; ?>
<table width="660" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" class="top_first_td"><? echo 'Create your clan for '?><img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;">100 gold.</td>
  </tr>
  	<tr>
      <td colspan="2" style="padding-bottom:10px;"></td>
    </tr>
  <tr>
    <td width="200" class="normal_first_td"><? echo 'Clan name:'.$foutje1; ?></td>
    <td width="460" class="normal_td"><input type="text" name="clan_naam" value="" class="text_long" maxlength="20"></td>
  </tr>
  <tr>
    <td class="normal_first_td"><? echo 'Description:'.$foutje2; ?></td>
    <td class="normal_td"><input type="text" name="clan_beschrijving" value="" class="text_long" maxlength="50"></td>
  </tr>
    <tr>
    <td class="normal_first_td"><? echo 'Type:'.$foutje3; ?></td>
    <td class="normal_td"><select name="clan_type" class="text_select"><option selected><option>darkness<option>dragon<option>fighting<option>fire<option>grass<option>lightning<option>metal<option>psychic<option>water</select></td>
    </tr>
  	
    </tr>
  <tr>
    <td colspan="2" class="top_first_td"><? echo 'Security'; ?></td>
  </tr>
  	<tr>
      <td colspan="2" style="padding-bottom:10px;"></td>
    </tr>
  <tr>
    <td class="normal_first_td">&nbsp;</td>
    <td class="normal_td"><img src="includes/captcha.php" alt="<?php echo $txt['captcha']; ?>" title="<?php echo $txt['captcha']; ?>" /></td>
  </tr>
  <tr>
    <td class="normal_first_td"><? echo $txt['guardcode'].' '.$foutje12; ?></td>
    <td class="normal_td"><input name="captcha" type="text" class="text_long" maxlength="3" /></td>
  </tr>
  <tr>
    <td class="normal_first_td">&nbsp;</td>
    <td class="normal_td"><button type="submit" name="make" class="button"><? echo 'Create Clan'; ?></button><? mysql_query("UPDATE `gebruikers` SET `clan-own`=`clan-own`+1 WHERE `login`='$data->login'"); ?></td>
  </tr>
</table>
</form>