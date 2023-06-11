<?
session_start();

include_once('config.php');
include_once('ingame.inc.php');

$gebruiker_item = mysql_fetch_assoc(mysql_query("SELECT * FROM `gebruikers_tmhm` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
	header("Location: index.php?page=home");
}
if($_POST['annuleer']){ 
    header("Location: index.php?page=items");
}
#Als er een result is kan pokemon evolueren.
if(isset($_POST['kies'])){
	if(empty($_POST['pokemonid'])) $error =  '<div class="red">Geen pokemon gekozen.</div>';
	else header('Location: ?page=includes/use_attack_finish&name='.$_GET['name'].'&pokemonid='.$_POST['pokemonid'].'');
}
?>
  <?php	
  		if($error) echo $error;
		echo "<center><div style='padding-bottom:10px;'>Kies de Pokemon die je ".$_GET['name']." wil leren.</div></center>";
		
		$check = mysql_fetch_assoc(mysql_query("SELECT type1, type2 FROM tmhm WHERE `naam`='".$_GET['name']."'"));
		#Pokemon loading the user that he has bagging
		$poke = mysql_query("SELECT pokemon_wild.wild_id, pokemon_wild.type1, pokemon_wild.type2, pokemon_wild.naam, pokemon_speler.id, pokemon_speler.level, pokemon_speler.shiny, pokemon_speler.user_id FROM pokemon_wild 
							INNER JOIN pokemon_speler 
							ON pokemon_speler.wild_id = pokemon_wild.wild_id 
							WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
  ?>
    
<form method="post">
<center>
<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr> 
		<td width="50"><center><strong>&raquo;</strong></center></td>
		<td width="100"><strong>Pokemon:</strong></td>
		<td width="150"><strong>Naam:</strong></td>
		<td width="100" align="center"><strong>Level:</strong></td>
		<td width="100" align="center"><strong>Kan aanval leren:</strong></td>
	</tr>
<?

#Pokemons die hij opzak heeft weergeven
for($teller=0; $pokemon = mysql_fetch_assoc($poke); $teller++){

  $kan = "<img src='../images/icons/red.png' alt='Kan niet'>";
  $disabled = 'disabled';
  #Heeft de stone werking?
  if($check['type1'] == $pokemon['type1']){
  	$kan = "<img src='../images/icons/green.png' alt='Kan wel'>";
  	$disabled = '';
  }
  if($check['type1'] == $pokemon['type2']){
    $kan = "<img src='../images/icons/green.png' alt='Kan wel'>";
    $disabled = '';
  }
  if($check['type2'] ==  $pokemon['type1']){
    $kan = "<img src='../images/icons/green.png' alt='Kan wel'>";
    $disabled = '';
  }
  if($check['type2'] == $pokemon['type2']){
    $kan = "<img src='../images/icons/green.png' alt='Kan wel'>";
    $disabled = '';
  }

  $pokemon = pokemonei($pokemon);
  $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);

  echo '<tr>';
  
  if($pokemon['ei'] != 1){
    echo '<td><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'" '.$disabled.'/></center></td>';             
  }
  else echo '<td><center><input type="radio" id="niet'.$i.'" name="niet" disabled/></center></td>';

  echo '<td><center><img src="../'.$pokemon['animatie'].'" width="32" height="32"></center></td>
    	<td>'.$pokemon['naam'].'</td>
    	<td align="center">'.$pokemon['level'].'</td>';
  
  #Als pokemon geen baby is
  if($pokemon['ei'] != 1) echo '<td align="right">'.$kan.'</td>';
  else echo '<td align="right"><img src="../images/icons/red.png" alt="Kan niet"></td>';

  echo '</tr>';
  }

  ?>
  <tr> 
    <td colspan="5"><input type="submit" name="kies" value="Geven" class="button_mini">
    <input type="submit" name="annuleer" value="Annuleren" class="button"></td>
  </tr>
</table>
</center>
</form>