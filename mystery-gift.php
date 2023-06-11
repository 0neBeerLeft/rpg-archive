<?
// Mystery_gift Ver 0.1
	include("includes/security.php");
	
	$page = 'gift';
	// Include Language File
	include_once('language/language-pages.php');



if(isset($_POST['get'])){
        $check = mysql_query("SELECT * FROM mystery_gift");

        $success = false;

        while($item = mysql_fetch_array($check)){
            if($item['users'] == $gebruiker['user_id']){
                $success = true;
            }

        }
	// Check if inserted code.
	if(empty($_POST['code'])){ 
		echo '<div class="red">Je hebt geen code ingevoerd.</div>';
	}
	// Check if code is vailed.
	elseif(mysql_num_rows(mysql_query("SELECT code FROM mystery_gift WHERE code = '".$_POST['code']."'")) == 0){
		echo '<div class="red">Code is verkeerd!</div>';
	}
	// Check if code is avaible.
	elseif(mysql_num_rows(mysql_query("SELECT code FROM mystery_gift WHERE code = '".$_POST['code']."' and uses ")) == 0){
		echo '<div class="red">De code is verlopen.</div>';
	}
	elseif($success) echo '<div class="red">Je hebt al een code gebruikt.</div>';
	else{
  		// Load mystery gift info
		  $gift = mysql_fetch_array(mysql_query("SELECT `id`, `code`, `gold`, `silver`, `pdays`, `candy`, `masterball` FROM mystery_gift WHERE code = '".$_POST['code']."'"));
      	  $user = mysql_fetch_array(mysql_query("SELECT `user_id`, `voornaam`, `achternaam`, `email` FROM gebruikers WHERE username = '".$gebruiker['username']."'"));
  			mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$gift['silver']."', `premiumaccount`=`premiumaccount`+'".$gift['pdays']."', `gold`=`gold`+'".$gift['gold']."' WHERE `username`='".$gebruiker['username']."'");
  			mysql_query("UPDATE `mystery_gift` SET `uses`=`uses`-'1' WHERE code = '".$_POST['code']."'");
			mysql_query("UPDATE `mystery_gift` SET `users`='".$gebruiker['user_id']."' WHERE `code` = '".$_POST['code']."' ");
  			mysql_query("UPDATE `gebruikers_item` SET `Masterball`=`masterball`+'".$gift['masterball']."', `Sonderbonbon`=`Sonderbonbon`+'".$gift['candy']."' WHERE `user_id`='".$_SESSION['id']."'");
  			
		  #Prize Message
		  if($gift['pdays'] > 0) { $g_pdays = '<b>'.$gift["pdays"].'</b> Premium,'; }
		  if($gift['gold'] > 0) { $g_gold = '<b>'.$gift["gold"].'</b> Gold, '; }
		  if($gift['silver'] > 0) { $g_silver = '<b>'.$gift["silver"].'</b> Silver, '; }
		  if($gift['candy'] > 0) { $g_candy = '<b>'.$gift["candy"].'</b> Candy. '; }
		  if($gift['masterball'] > 0) { $g_mball = '<b>'.$gift["masterball"].'</b> Masterball, '; }
 echo '<div class="green">Gefeliciteerd! je hebt '.$g_pdays.''.$g_gold.''.$g_silver.''.$g_mball.''.$g_candy.' ontvangen!.</div>';
	}
}
?>
<style>
       .myButton {
        
        -moz-box-shadow:inset 0px 1px 0px 0px #54a3f7;
        -webkit-box-shadow:inset 0px 1px 0px 0px #54a3f7;
        box-shadow:inset 0px 1px 0px 0px #54a3f7;
        
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #007dc1), color-stop(1, #0061a7));
        background:-moz-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-webkit-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-o-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-ms-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:linear-gradient(to bottom, #007dc1 5%, #0061a7 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#007dc1', endColorstr='#0061a7',GradientType=0);
        
        background-color:#007dc1;
        
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        border-radius:3px;
        
        border:1px solid #124d77;
        
        display:inline-block;
        color:#ffffff;
        font-family:arial;
        font-size:13px;
        font-weight:normal;
        padding:6px 24px;
        text-decoration:none;
        margin:2px;
        text-shadow:0px 1px 0px #154682;
        
    }
    .myButton:hover {
        
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0061a7), color-stop(1, #007dc1));
        background:-moz-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-webkit-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-o-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-ms-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:linear-gradient(to bottom, #0061a7 5%, #007dc1 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0061a7', endColorstr='#007dc1',GradientType=0);
        
        background-color:#0061a7;
    }
    .myButton:active {
        position:relative;
        top:1px;
    }
    input  {
    border:1px solid #ccc;
    border-radius:3px
    height:20px;
    line-height:20px;
    font-size:16px;
    }
</style>
<center>
<img width="350px" src="images/secretgift.png"><br>
<h2> Geheime code </h2>
Hier kan je geheime codes inwisselen voor mooie prijzen! <br/>
Vul de code in het onderstaande veld om de prijs te innen. <br/> <br/>

<div class="blue">Een geheime code ontvang je tijdens een website event, bijvoorbeeld een actie of een feestdag.</div> <br/> <br/>
<b> Het geheime pakket kan een of meer van de volgende items bevatten: </b> <br/>
Zilver | Goud | Master Balls | Speciale Sweets<br/><br/>
<hr></b>
<form method="post">
<table style="background:#efefef;border:1px solid #888;border-radius:2px;padding:20px">
	<tr>
    	<td>Code invoeren:</td>
        <td><input type="text" name="code" class="text_gift" maxlength="16" /></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td><cecnter><input type="submit" name="get" value="voorleggen" class="myButton" /><center></td>
    </tr>
</table>
</form>
</center>
