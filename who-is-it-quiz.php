<?php 
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'who-is-it-quiz';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

	$keuzessql = mysql_query("SELECT wild_id, naam FROM pokemon_wild ORDER BY naam ASC");
	$pass = 1;
	
	//Kijken of je dit uur alweer mag
	$lasttime	            = strtotime($gebruiker['wiequiz']);
	$current_time           = strtotime(date('Y-m-d H:i:s'));
	$countdown_time         = 3600-($current_time-$lasttime);
	
	//Is de sessie leeg en zijn je punten op?
	if(empty($_SESSION['who_is_that_img']) && ($countdown_time > 0)){
		echo '<div class="blue">'.$txt['alert_wait'].'</div>';
		$pass = 0;
	}
	//Als de sessie leeg is maar je nog wel punten hebt, nieuwe sessie maken:
	elseif(empty($_SESSION['who_is_that_img']) && ($countdown_time <= 0)){
		
		//Updaten dat de pokemon er is.
		$datenow = date('Y-m-d H:i:s');
		mysql_query("UPDATE gebruikers SET wiequiz = '".$datenow."' WHERE user_id = '".$_SESSION['id']."'");
			
		//Haal een pokemon uit de database
		$pkmn = mysql_fetch_assoc(mysql_query("SELECT wild_id FROM pokemon_wild ORDER BY rand() limit 1"));
		$shinyrand = rand(1,2);
		if($shinyrand == 1) $status = 'shiny';
		else $status = 'pokemon';
		//Sessie zetten voor het plaatje	
		$_SESSION['who_is_that_img'] = $pkmn['wild_id'].'/'.$status;
	}
	
	//Code splitten, zodat informatie duidelijk word
  	if(!empty($_SESSION['who_is_that_img'])) list ($answer, $status) = split ('[/]', $_SESSION['who_is_that_img']);
	
	//Als er op de knop word geklikt
	if(isset($_POST['submit']) && ($pass != 0)){
		if($_POST['who'] == '0'){
			echo '<div class="red">'.$txt['alert_choose_a_pokemon'].'</div>';
		}
		elseif(empty($_SESSION['who_is_that_img'])){
			echo '<div class="red">'.$txt['alert_no_answer'].'</div>';
		}
		else{
			$pass = 0;

			//Kijken of de speler het antwoord goed heeft
			if($_POST['who'] == $answer){
				echo '<div class="green">'.$txt['success_win'].'</div>';
				mysql_query("UPDATE gebruikers SET silver = silver+'200' WHERE user_id = '".$_SESSION['id']."'");
				rankerbij('whoisitquiz',$txt);
			}
			else{
				$answersql = mysql_fetch_assoc(mysql_query("SELECT naam FROM pokemon_wild WHERE wild_id = '".$answer."'"));
				echo '<div class="red">'.$txt['success_lose_1'].' '.$answersql['naam'].'. '.$txt['success_lose_2'].'</div>';
				rankeraf('whoisitquiz');
			}
			//Sessie leegmaken
			unset($_SESSION['who_is_that_img']);
		}
	}
	if($pass != 0){
	
?>
<form method="post">
<center>
<p><?php echo $txt['title_text']; ?></p>
<table width="140">
    <tr>
    	<td><div style="padding:0px 0px 10px 5px;"><img src="images/<?php echo $status; ?>/<?php echo $answer; ?>.gif" alt="<?php echo $txt['who_is_it']; ?>" style="filter:xray;"></div></td>
    </tr>
    <tr>
    	<td>
<select name="who" class="text_select">
<option value="0"><?php echo $txt['choose_a_pokemon']; ?></option>
<?php 
	while($keuzes = mysql_fetch_assoc($keuzessql)){
	echo '<option value="'.$keuzes['wild_id'].'">'.$keuzes['naam'].'</option>';
}
?>
</select></td>
	</tr>
    <tr>
    	<td><button type="submit" name="submit" class="button" <?php echo $disable; ?>><?php echo $txt['button']; ?></button></td>
    </tr>
</table>
</center>
</form>
<?php } ?>

      <script type="text/javascript">  	
      var int3 = <? echo $countdown_time ?>;   //Zet hier je Variabele neer of het aantal SECONDEN
      function aftellen3() {  	
        var inter3 = int3;  
        var uren3 = inter3 / 3600;  	
        var uur3 = Math.floor(uren3); 
        var gehad3 = uur3 * 3600;
        var moetnog3 = inter3 - gehad3;  
        var minuten3 = moetnog3 / 60;
        var mins3 = Math.floor(minuten3);  
        var gehadmin3 = mins3 * 60;  
        var moetnogg3 = moetnog3 - gehadmin3;  
        var secs3 = moetnogg3;  
        
        if(inter3 <= 0) {  
          clearInterval(interval3);
		  document.location.reload()
        } else {  
          int3 = inter3 - 1;  
     
          document.getElementById('uur3').innerHTML = uur3;     
          document.getElementById('minuten3').innerHTML = mins3;  
          document.getElementById('seconden3').innerHTML = secs3;  
        }  
      }
        // functie 1e keer uitvoeren 
        aftellen3();  
        // functie elke 1000 microsec. uitv. 
        interval3 = setInterval('aftellen3();', 1000);
    </script> 