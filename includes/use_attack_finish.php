<?php
session_start();

include_once('config.php');
include_once('ingame.inc.php');

$gebruiker_item = mysql_fetch_assoc(mysql_query("SELECT * FROM `gebruikers_tmhm` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
	header("Location: index.php?page=home");
}

?>
<?php
  #Gegevens laden van de des betreffende pokemon
  $pokemoninfo  = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.wild_id, pokemon_wild.naam, pokemon_wild.type1, pokemon_wild.type2, pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.aanval_1, pokemon_speler.aanval_2, pokemon_speler.aanval_3, pokemon_speler.aanval_4 FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE `id`='".$_GET['pokemonid']."'"));
  #Naam van de aanval
  $attacknaam = mysql_fetch_assoc(mysql_query("SELECT omschrijving FROM tmhm WHERE naam = '".$_GET['name']."'"));
  
  $check = mysql_fetch_assoc(mysql_query("SELECT * FROM tmhm WHERE `naam`='".$_GET['name']."'"));
	
	if($_POST['annuleer']){ 
	header("Location: index.php?page=items");
	}
	if(empty($_GET['pokemonid'])){
		echo '<div class="red"><img src="../images/icons/red.png"> No pokemon chosen!</div>';
		$foutje = 1;
	}
	elseif($check['type1'] != $pokemoninfo['type1'] && $check['type1'] != $pokemoninfo['type2'] && $check['type2'] != $pokemoninfo['type1'] && $check['type2'] != $pokemoninfo['type2']){
		echo '<div class="red"><img src="../images/icons/red.png"> Your '.$_GET['name'].' can not learn that attack.</div>';
		$foutje = 1;
	}
	elseif($pokemoninfo['user_id'] != $_SESSION['id']){
  	echo '<div class="red"><img src="../images/icons/red.png"> This pokemon is not yours.</div>';
		$foutje = 1;
	}
	elseif($pokemoninfo['aanval_1'] == $check['omschrijving'] OR $pokemoninfo['aanval_2'] == $check['omschrijving'] OR $pokemoninfo['aanval_3'] == $check['omschrijving'] OR $pokemoninfo['aanval_4'] == $check['omschrijving']){
  	echo '<div class="red"><img src="../images/icons/red.png"> '.$pokemoninfo['naam'].' heeft de aanval '.$check['omschrijving'].' al geleerd.</div>';
		$foutje = 1;
	}
	else{		
		if(isset($_POST['attack'])){
			mysql_query("UPDATE pokemon_speler SET aanval_".$_POST['welke']." = '".$check['omschrijving']."' WHERE id = '".$_GET['pokemonid']."'");
			mysql_query("UPDATE gebruikers_item SET items = items -'1'");
			
			$kortenaam  = substr($_GET['name'], 0, -2);
			if($kortenaam == 'TM') mysql_query("UPDATE gebruikers_tmhm SET ".$_GET['name']." = ".$_GET['name']." -'1' WHERE user_id='".$_SESSION['id']."'");
			header("Location: index.php?page=items");
		}
	}
	
	if($foutje == 1){
		echo $error;
	}
	else{
?>
	
	<center>
      <table width="500" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="130" rowspan="4"><img src="../images/pokemon/<? echo $pokemoninfo['wild_id']; ?>.png" /></td>
        <td colspan="2">Geef <? echo $pokemoninfo['naam']; ?> <strong><? echo $_GET['name']; ?></strong> <?php echo $attacknaam['omschrijving']; ?>.<br />
        Tip: denk aan het type van de aanval.<br /><br /></td>
      </tr>
      <?
      echo '<tr>
        	  	<form method="post">
				<td width="178"><input type="submit" name="attack" value="'.$pokemoninfo['aanval_1'].'" class="button"></td>
				<input type="hidden" name="welke" value="1">
        		</form>
        		<form method="post">
          		<td width="178"><input type="submit" name="attack" value="'.$pokemoninfo['aanval_2'].'" class="button"></td>
          		<input type="hidden" name="welke" value="2">
        		</form>
      	</tr>
      	<tr>
        		<form method="post">
          		<td><input type="submit" name="attack" value="'.$pokemoninfo['aanval_3'].'" class="button"></td>
          		<input type="hidden" name="welke" value="3">
       		</form>
        		<form method="post">
          		<td><input type="submit" name="attack" value="'.$pokemoninfo['aanval_4'].'" class="button"></td>
          		<input type="hidden" name="welke" value="4">
       		</form>
     	 	</tr>';
      ?> 
      <tr>
          <td colspan="2"><form method="post"><input type="submit" name="annuleer" value="Annuleer aanval" class="button"></form></td>
      </tr>
    </table>
  </center>
<?php } ?>