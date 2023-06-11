<?
if(($gebruiker['rank'] < 3) || ($gebruiker['in_hand'] == 0)) header('Location: index.php');

#Load language
$page = 'race-invite';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$getname = $_GET['player'];

if((isset($_POST['submit']))){
  if(!empty($_POST['naam'])) $getname = $_POST['naam'];
  if($_POST['what'] == 'silver') $amountcheck = $gebruiker['silver'];
  else $amountcheck = $gebruiker['gold'];
  
  #checks
  if($gebruiker['races'] < 1) echo '<div class="red">'.$txt['alert_no_races_today'].'</div>';
  elseif(empty($_POST['naam'])) echo '<div class="red">'.$txt['alert_no_player'].'</div>';
  elseif($_SESSION['naam'] == $_POST['naam']) echo '<div class="red">'.$txt['alert_not_yourself'].'</div>';
  elseif($_POST['bedrag'] < 0) echo '<div class="red">'.$txt['alert_unknown_amount'].'</div>';
  elseif(!ctype_digit($_POST['bedrag'])) echo '<div class="red">'.$txt['alert_unknown_amount'].'</div>';
  elseif(($_POST['what'] != 'silver') && ($_POST['what'] != 'gold')) echo '<div class="red">'.$txt['alert_unknown_what'].'</div>';
  elseif($_POST['bedrag'] == 0) echo '<div class="red">'.$txt['alert_no_amount'].'</div>';
  elseif($amountcheck < $_POST['bedrag']) echo '<div class="red">'.$txt['alert_not_enough_silver_or_gold'].'</div>';
  else{
    $sql = mysql_query("SELECT user_id, wereld, land, rank, admin FROM gebruikers WHERE username='".$_POST['naam']."'");
	$select = mysql_fetch_assoc($sql);
    if(mysql_num_rows($sql) == 0) echo '<div class="red">'.$txt['alert_user_unknown'].'</div>';
    elseif($select['wereld'] != $gebruiker['wereld']) echo '<div class="red">'.$_POST['naam'].' '.$txt['alert_opponent_not_in'].' '.$gebruiker['wereld'].'.</div>';
	elseif($select['rank'] < 4) echo '<div class="red">'.$_POST['naam'].' '.$txt['alert_opponent_not_casual'].'</div>';
    else{
        $time = time();
		$code = rand(100000,999999);

		if($_POST['what'] == 'silver'){
			$silver = $_POST['bedrag'];
			$gold = 0;
			$type = 'silver';
			$amount = $_POST['bedrag'];
		}
		else{
			$silver = 0;
			$gold = $_POST['bedrag'];
			$type = 'gold';
			$amount = $_POST['bedrag'];
		}
		
		#In race invoegen
        mysql_query("INSERT INTO races (race_id, uitdager, tegenstander, silver, gold, time, code)
          VALUES (NULL, '".$_SESSION['id']."', '".$select['user_id']."', '".$silver."', '".$gold."', '".$time."', '".$code."')");
		$raceinputid = mysql_insert_id();
		
		#Taalpack includen van events
		//$eventlanguage = GetEventLanguage($select['land']);
		include('language/events/language-events-nl.php');
		
		#Bericht opstellen na wat de language van de user is
		$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> '.$gebruiker['username'].' '.$txt['event_want_race'].' <img src="images/icons/'.$type.'.png" width="16" height="16" class="imglower"> '.highamount($amount).' '.$type.'. <a href="?page=race&id='.$raceinputid.'&code='.$code.'&accept=1">'.$txt['event_accept'].'</a>, <a href="?page=race&id='.$raceinputid.'&code='.$code.'&accept=0">'.$txt['event_deny'].'</a>.';
		
		#Een event sturen naar tegenstander
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '".$select['user_id']."', '".$event."', '0')");
		
		#Gebruiker updaten
		mysql_query("UPDATE gebruikers SET silver = silver-'".$silver."', gold = gold-'".$gold."', races = races-'1' WHERE user_id = '".$_SESSION['id']."'");
		
		echo '<div class="green">'.$txt['success'].'</div>';
		
		$gebruiker['races'] = $gebruiker['races']-1;

    }
  }
}
?>

<form method="post">
  <center>
    <p><?php echo $txt['title_text'].'<br /><br />
	   '.$txt['races_left_today'].' <strong>'.$gebruiker['races'].'</strong>.';
	   if($gebruiker['premiumaccount'] == 0) echo '<br />'.$txt['premium_10_times']; ?></p>
    <table width="300">
      <tr>
        <td><label for="naam"><img src="images/icons/user.png" width="16" height="16" alt="Player" class="imglower" /> <?php echo $txt['player']; ?></label></td>
        <td colspan="2"><input type="text" name="naam" id="naam" class="text_long" value="<?php echo $getname; ?>" /></td>
      </tr>
      <tr>
        <td width="150"><label for="silver"><img src="images/icons/vraag.png" width="16" height="16" alt="Silver/Gold" class="imglower" /> <?php echo $txt['silver_or_gold']; ?></label></td>
        <td width="50"><input type="radio" name="what" value="silver" id="silver" <?php if($_POST['what'] != 'gold') echo 'checked'; ?> /> <label for="silver"><img src="images/icons/silver.png" alt="Silver" title="Silver" width="16" height="16" /></label></td>
        <td width="100"><input type="radio" name="what" value="gold" id="gold" <?php if($_POST['what'] == 'gold') echo 'checked'; ?> /> <label for="gold"><img src="images/icons/gold.png" alt="Gold" title="Gold" width="16" height="16" /></label></td>
      </tr>
      <tr>
        <td><label for="amount"><img src="images/icons/silver-gold.png" width="16" height="16" alt="Silver/Gold" class="imglower" /> <?php echo $txt['amount']; ?></label></td>
        <td colspan="2"><input type="text" name="bedrag" id="amount" value="<?php if(!empty($_POST['bedrag'])) echo $_POST['bedrag']; else echo 0; ?>" class="text_long"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><button type="submit" name="submit" class="button"><?php echo $txt['button']; ?></button</td>
      </tr>
  </table>
  <?php 
  $racesql = mysql_query("SELECT races.tegenstander, races.silver, races.gold, races.time, gebruikers.username FROM races INNER JOIN gebruikers ON races.tegenstander = gebruikers.user_id WHERE races.uitdager = '".$_SESSION['id']."' ORDER BY races.race_id ASC");
  $aantalraces = mysql_num_rows($racesql);
  if($aantalraces > 0){
	  echo '<HR>
		  <p><strong>'.$txt['races_opened'].'</strong><br />
		  '.$txt['races_deleted_3_days'].'</p>
		  
		  <table width="440" cellpadding="0" cellspacing="0">
		  	<tr>
				<td width="50" class="top_first_td">'.$txt['#'].'</td>
				<td width="150" class="top_td">'.$txt['opponent'].'</td>
				<td width="120" class="top_td">'.$txt['price'].'</td>
				<td width="120" class="top_td">'.$txt['when'].'</td>
			</tr>';
		  for($num = 1; $race = mysql_fetch_assoc($racesql); $num++){
			  if($race['silver'] == 0){
				  $type = 'gold';
				  $amount = $race['gold'];
			  }
			  else{
				  $type = 'silver';
				  $amount = $race['silver'];
			  }
			  
			  echo '<tr>
			  			<td class="normal_first_td">'.$num.'.</td>
						<td class="normal_td"><a href="?page=profile&player='.$race['username'].'">'.$race['username'].'</a></td>
						<td class="normal_td"><img src="images/icons/'.$type.'.png" width="16" height="16" alt="'.$type.'" style="margin-bottom:-3px;"> '.highamount($amount).'</td>
						<td class="normal_td">'.date("d-m-Y H:i", $race['time']).'</td>
					</tr>';
		  }
		  
		  echo '</table>';
  }
  else{
	  echo '<HR>
	  <p>'.$txt['no_races_opened'].'</p>';
  }
  ?>
  </center>
</form>