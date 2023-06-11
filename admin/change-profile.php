<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 2) header('location: index.php?page=home');

###################################################################

if(isset($_GET['player'])){
	  
	  //Gegevens laden van de ingevoerde gebruiker
	  $profiel = mysql_fetch_array(mysql_query("SELECT `username`, `datum`, `email`, `ip_aangemeld`, `ip_ingelogd`, `premiumaccount`, `silver`, `gold`, `bank`, `admin`, `wereld`, `online`, `voornaam`, `achternaam`, `land`, `character`, `profiel`, `teamzien`, `rank`, `aantalpokemon`, `gewonnen`, `verloren`, COUNT(DISTINCT `user_id`) AS `aantal` FROM `gebruikers` WHERE `username`='".$_GET['player']."' GROUP BY `user_id`"));
	  
	  //is er geen player ingevuld dan terug naar home
	  if($profiel['aantal'] != 1) header("Location: index.php?page=home");
    //Anders de pagina
    else{
		
		$plaatssql = mysql_query("SELECT `username` FROM `gebruikers` WHERE `account_code`='1'AND admin = '0' ORDER BY `rank` DESC, `rankexp` DESC, `username` ASC");
		
	  //Default Values
      $medaille = "";
      $plaatje = "images/icons/status_offline.png";
      $online  = "Offline";
	  
	  for($j=1; $plaats = mysql_fetch_array($plaatssql); $j++)
        if($profiel['username'] == $plaats['username']) $voortgang = $j;
      
	    $voortgangplaats = $voortgang."<sup>e</sup>";
	  
      if($voortgang == '1'){
		    $medaille = "<img src='images/icons/plaatsnummereen.png'>";
		    $voortgangplaats = $voortgang."<sup>ste</sup>";
		  }
		  elseif($voortgang == '2')
		    $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
		  elseif($voortgang == '3')
		    $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
		  elseif($voortgang > '3' && $voortgang <= '10')
		    $medaille = "<img src='images/icons/gold.png'>";
		  elseif($voortgang > '10' && $voortgang <= '30')
		    $medaille = "<img src='images/icons/silver.png'>";
		  elseif($voortgang > '30' && $voortgang <= '50')
		    $medaille = "<img src='images/icons/bronze.png'>";
		elseif($voortgang =='')
			$voortgangplaats = "<b>Admin.</b>";
			
		  //Tijd voor plaatje
		  $tijd = time();
		  if(($profiel['online']+300) > $tijd){
			$plaatje = "images/icons/status_online.png";
			$online  = "Online";
		  }
	  
	    $land 	      = $_POST['land'] == ''       ? $profiel['land']       : $_POST['land'];
        $character 	  = $_POST['character'] == ''   ? $profiel['character']   : $_POST['character'];
		$teamzien     = $_POST['teamzien'] == '' ? $profiel['teamzien'] : $_POST['teamzien'];
		$rank     = $_POST['rank'] == '' ? $profiel['rank'] : $_POST['rank'];
		
	  ##### Als er op de knop is gedrukt #####
	  
	  if(isset($_POST['change'])){
		    mysql_query("UPDATE `gebruikers` SET `character`='".$_POST['character']."', `username`='".$_POST['username']."', `premiumaccount`='".$_POST['premiumaccount']."', `voornaam`='".$_POST['voornaam']."', `achternaam`='".$_POST['achternaam']."', `land`='".$_POST['land']."', `wereld`='".$_POST['wereld']."', `datum`='".$_POST['datum']."', `rank`='".$_POST['rank']."', `aantalpokemon`='".$_POST['aantalpokemon']."', `gewonnen`='".$_POST['gewonnen']."', `verloren`='".$_POST['verloren']."', `email`='".$_POST['email']."', `ip_aangemeld`='".$_POST['ip_aangemeld']."' , `ip_ingelogd`='".$_POST['ip_ingelogd']."', `teamzien`='".$_POST['teamzien']."', `silver`='".$_POST['silver']."', `gold`='".$_POST['gold']."', `bank`='".$_POST['bank']."', `profiel`='".$_POST['profiel']."' WHERE `username`='".$_GET['player']."'");
	  
	  		echo '<div class="green"><img src="images/icons/green.png">Successfully updated '.$_GET['player'].'s profile!</div>';
	  }
?>
<form method="post">
    <center>
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="180" rowspan="23" valign="top"><img src="images/you/<? echo $profiel['character']; ?>.png" /><br />
      <select name="character" value="<?php if(isset($_POST ['character']) && !empty($_POST ['character'])) { echo $_POST ['character']; }?>" class="text_select">
      <?
      $charactersql = mysql_query("SELECT naam FROM characters ORDER BY id ASC");
      
      if(isset($_POST['character'])){
        $characterr = $_POST['character'];
      }
      else{
        $characterr = $profiel['character'];
      } 
  
      while($character = mysql_fetch_array($charactersql)){
        if($character['naam'] == $characterr){
          $selected = 'selected';
        }
        else{
          $selected = '';
        }
        echo '<option value="'.$character['naam'].'" '.$selected.'>'.$character['naam'].'</option>';
      }
      ?>
      </select></td>
        </tr>
        <tr>
          <td height="20"><strong>Username:</strong></td>
          <td><input name="username" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['username']; else echo $_POST['username']; ?>" maxlength="10" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Premium days:</strong></td>
          <td><input name="premiumaccount" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['premiumaccount']; else echo $_POST['premiumaccount']; ?>" maxlength="4" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Name:</strong></td>
          <td><input name="voornaam" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['voornaam']; else echo $_POST['voornaam']; ?>" maxlength="12" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Surname:</strong></td>
          <td><input name="achternaam" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['achternaam']; else echo $_POST['achternaam']; ?>" maxlength="12" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Country:</strong></td>
          <td><select name="land" class="text_select">
    <?
    $landsql = mysql_query("SELECT `en`, `nl` FROM `landen` ORDER BY `".$lang['taalshort']."` ASC"); 

    if(isset($land))
      $landd = $land;
    else
      $landd = $profiel['land'];

    while($land = mysql_fetch_array($landsql)){
      $selected = '';
      if($land['en'] == $landd)
        $selected = 'selected';
        
      echo '<option value="'.$land['en'].'" '.$selected.'>'.$land[$lang['taalshort']].'</option>';
    }
    ?>
    </select></td>
        </tr>
        <tr>
          <td height="20"><strong>Started:</strong></td>
          <td><input type="text" name="datum" value="<? if(!isset($_POST['change'])) echo $profiel['datum']; else echo $_POST['datum']; ?>" class="text_long" /></td>
        </tr>
        <tr>
          <td height="20" colspan="2">&nbsp;</td>
        </tr>
        <?php
		
		##Maken dat hij goeie wereld pakt!
		//standaardwaarden
		$kantoselected = '';
		$johtoselected = '';
		$hoennselected = '';
		$sinnohselected = '';
		
		if(isset($_POST['change'])){
			if($_POST['wereld'] == 'Kanto') $kantoselected = 'selected';
			elseif($_POST['wereld'] == 'Johto') $johtoselected = 'selected';
			elseif($_POST['wereld'] == 'Hoenn') $hoennselected = 'selected';
			elseif($_POST['wereld'] == 'Sinnoh') $sinnohselected = 'selected';
			elseif($_POST['wereld'] == 'Unova') $unovaselected = 'selected';
		}
		else{
			if($profiel['wereld'] == 'Kanto') $kantoselected = 'selected';
			elseif($profiel['wereld'] == 'Johto') $johtoselected = 'selected';
			elseif($profiel['wereld'] == 'Hoenn') $hoennselected = 'selected';
			elseif($profiel['wereld'] == 'Sinnoh') $sinnohselected = 'selected';
			elseif($profiel['wereld'] == 'Unova') $unovaselected = 'selected';
		}
		
		?>
        
        <tr>
          <td height="20"><strong>World:</strong></td>
          <td height="20"><select name="wereld" class="text_select">
          					<option value="Kanto" <? echo $kantoselected; ?>>Kanto</option>
                            <option value="Johto" <? echo $johtoselected; ?>>Johto</option>
                            <option value="Hoenn" <? echo $hoennselected; ?>>Hoenn</option>
                            <option value="Sinnoh" <? echo $sinnohselected; ?>>Sinnoh</option>
                            <option value="Unova" <? echo $unovaselected; ?>>Unova</option>
                          </select></td>
        </tr>
        <tr>
          <td height="20"><strong>Silver:</strong></td>
          <td height="20"><input type="text" name="silver" value="<? if(!isset($_POST['change'])) echo $profiel['silver']; else echo $_POST['silver']; ?>" class="text_long" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Gold:</strong></td>
          <td height="20"><input type="text" name="gold" value="<? if(!isset($_POST['change'])) echo $profiel['gold']; else echo $_POST['gold']; ?>" class="text_long" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Bank:</strong></td>
          <td height="20"><input type="text" name="bank" value="<? if(!isset($_POST['change'])) echo $profiel['bank']; else echo $_POST['bank']; ?>" class="text_long" /></td>
        </tr>
        <tr>
          <td height="20" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td height="20"><strong>Rank:</strong></td>
          <td><select name="rank" class="text_select">
    <?
    $ranksql = mysql_query("SELECT naam, ranknummer FROM rank ORDER BY ranknummer DESC"); 

    if(isset($rank))
      $rankk = $rank;
    else
      $rankk = $profiel['rank'];

    while($rank = mysql_fetch_array($ranksql)){
      $selected = '';
      if($rank['ranknummer'] == $rankk)
        $selected = 'selected';
        
      echo '<option value="'.$rank['ranknummer'].'" '.$selected.'>'.$rank['naam'].'</option>';
    }
    ?>
    </select></td>
        </tr>
        <tr>
          <td height="20"><strong>Rank:</strong></td>
          <td><? echo $medaille; ?> <? echo $voortgangplaats; ?></td>
        </tr>
        <tr>
          <td height="20"><strong>Pokemon owned:</strong></td>
          <td><input name="aantalpokemon" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['aantalpokemon']; else echo $_POST['aantalpokemon']; ?>" maxlength="3" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Battles won:</strong></td>
          <td><input name="gewonnen" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['gewonnen']; else echo $_POST['gewonnen']; ?>" maxlength="9" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Battles lost:</strong></td>
          <td><input name="verloren" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['verloren']; else echo $_POST['verloren']; ?>" maxlength="9" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Status:</strong></td>
          <td><img src="<? echo $plaatje; ?>" /> <? echo $online; ?></td>
        </tr>
        <tr>
          <td height="20" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td height="20"><strong>Email:</strong></td>
          <td><input type="text" name="email" value="<? if(!isset($_POST['change'])) echo $profiel['email']; else echo $_POST['email']; ?>" class="text_long" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Ip logged:</strong></td>
          <td><input name="ip_aangemeld" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['ip_aangemeld']; else echo $_POST['ip_aangemeld']; ?>" maxlength="15" /></td>
        </tr>
        <tr>
          <td height="20"><strong>Ip login:</strong></td>
          <td><input name="ip_ingelogd" type="text" class="text_long" value="<? if(!isset($_POST['change'])) echo $profiel['ip_ingelogd']; else echo $_POST['ip_ingelogd']; ?>" maxlength="15" /></td>
        </tr>
	</table>
</center>

            <hr />
            <center>
              <strong>Seeing Team:</strong><br />
			  <?php 
              if($teamzien == 1){
              echo'	<input type="radio" name="teamzien" value="1" id="ja" checked /><label for="ja" style="padding-right:17px"> Yes</label>
                    <input type="radio" name="teamzien" value="0" id="nee" /><label for="nee"> No</label>';
              }
              elseif($teamzien == 0){
              echo'	<input type="radio" name="teamzien" value="1" id="ja" /><label for="ja" style="padding-right:17px"> Yes</label>
                    <input type="radio" name="teamzien" value="0" id="nee" checked /><label for="nee"> No</label>';
              }
              else{
              echo'	<input type="radio" name="teamzien" value="1" id="ja" /><label for="ja" style="padding-right:17px"> Yes</label>
                    <input type="radio" name="teamzien" value="0" id="nee" /><label for="nee"> No</label>';
              }?>
</center>
            <hr />
			<textarea style="width:580px;" class="text_area" rows="15" name="profiel" ><? if(!isset($_POST['change'])) echo $profiel['profiel']; else echo $_POST['profiel']; ?></textarea>
            <br />
			<input type="submit" name="change" value="Change!" class="button" /><br />

<? 
}
}
?>
</form>