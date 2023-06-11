<?php 
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
$page = 'who-is-it-quiz';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

	$keuzessql = mysql_query("SELECT wild_id, naam FROM pokemon_wild WHERE wereld != 'Unova' ORDER BY naam ASC");
	$pass = 1;
	
	//Kijken of je dit uur alweer mag
	$lasttime	            = strtotime($gebruiker['scramble']);
	$current_time           = strtotime(date('Y-m-d H:i:s'));
	$countdown_time         = 3600-($current_time-$lasttime);
	
	//Is de sessie leeg en zijn je punten op?
	if(empty($_SESSION['scrambler']) && ($countdown_time > 0)){
		echo '<div class="blue">'.$txt['alert_wait'].'</div>';
		$pass = 0;
	}
	//Als de sessie leeg is maar je nog wel punten hebt, nieuwe sessie maken:
	elseif(empty($_SESSION['scrambler']) && ($countdown_time <= 0)){
		
		//Updaten dat de pokemon er is.
		$datenow = date('Y-m-d H:i:s');
		mysql_query("UPDATE gebruikers SET scramble = '".$datenow."' WHERE user_id = '".$_SESSION['id']."'");
			
		//Haal een pokemon uit de database
		$pkmn = mysql_fetch_assoc(mysql_query("SELECT naam FROM pokemon_wild WHERE wereld != 'Unova' ORDER BY rand() limit 1"));
		//Sessie zetten voor het plaatje	
		$_SESSION['scrambler'] = $pkmn['naam'];
	}
	
	//Code splitten, zodat informatie duidelijk word
  	if(!empty($_SESSION['scrambler'])) list ($answer, $status) = preg_split ('[/]', $_SESSION['scrambler']);
	
	//Als er op de knop word geklikt
	if(isset($_POST['submit']) && ($pass != 0)){
		if(empty($_POST['who'])){
			echo '<div class="red">Niets is ingevoerd!</div>';
		}
		else{
			$pass = 0;

			//Kijken of de speler het antwoord goed heeft
			if(($_POST['who'] == $answer) || ($_POST['who'] == strtolower($answer)) || ($_POST['who'] == strtoupper($answer))){
				echo '<div class="green">Jaaaaaa dat was de juiste naam je ontvangt  300 <img src="images/icons/silver.png"> .</div>';
				mysql_query("UPDATE gebruikers SET silver = silver+'300' WHERE user_id = '".$_SESSION['id']."'");
				rankerbij('scrambler',$txt);
			}
			else{
				$answersql = mysql_fetch_assoc(mysql_query("SELECT naam FROM pokemon_wild WHERE naam = '".$answer."'"));
				echo '<div class="red">Dit was de juiste naam:  '.$answersql['naam'].' geweest..</div>';
				rankeraf('scrambler');
			}
			//Sessie leegmaken
			unset($_SESSION['scrambler']);
		}
	}
	if($pass != 0){
	
?>
<form method="post">
<center>

<p><h2><img src="images/UnownA.gif"> Pokemon naam<img src="images/UnownA.gif"></h2></p>
<hr>
<div class="bubble"><img src="images/trainers/Gentleman Piet.png" style="float:left" class="flip">

Heheheh... Ik heb perongeluk mijn Pokédex  gecodeerd...<br/>

Help mij uitvogelen wie onderstaande Pokémon is en je ontvangt 300 <img src="images/icons/silver.png">silver.</div><br><br>
<table>
    <tr>
    	<td><font size=6><?php echo strtoupper(str_shuffle($answer)); ?></font></td>
    </tr>
    <tr>
    <td><input class="text_long" type="text" name="who" size="20" maxlength="50" /></td>
	</tr>
    <tr>
    	<td><button type="submit" name="submit" class="button" <?php echo $disable; ?>>Oplossen</button></td>
    </tr>
</table>
</center>
</form>
<?php } ?>

      <script type="text/javascript">  	
      var int3 = <?php echo $countdown_time ?>;   
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