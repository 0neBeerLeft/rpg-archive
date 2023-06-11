<?
//session_start();

//include_once('config.php');
include_once('ingame.inc.php');

$error = "<center><div style='padding-bottom:10px;'>Kies welke pokemon je een ".$_GET['name']." wilt geven.</div></center>"; 
$gebruiker_item = mysql_fetch_array(mysql_query("SELECT * FROM `gebruikers_item` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
  header("Location: index.php?page=home");
}
?>
<?
if($_POST['annuleer']){ 
    header("Location: index.php?page=items");
}
if((isset($_POST['spcitem'])) AND (isset($_POST['pokemonid']))){
  if($_POST['item'] == "Protein") $wat = "attack";  
  elseif($_POST['item'] == "Iron") $wat = "defence";
  elseif($_POST['item'] == "Carbos") $wat = "speed";
  elseif($_POST['item'] == "HP up") $wat = "hp";    
  elseif($_POST['item'] == "Calcium") $wat = "spc";  
  
  $check = mysql_fetch_array(mysql_query("SELECT `".$wat."_up` FROM pokemon_speler WHERE id='".$_POST['pokemonid']."'"));
  
  if($check[$wat.'_up'] >= 75){
    $error = '<div class="red"><img src="../images/icons/red.png" width="16" height="16" /> Je hebt al je pokemon van 25 '.$_POST['item'].' gegeven.';
  }
  else{
    if($wat == "spc") mysql_query("UPDATE `pokemon_speler` SET `spc.attack`=`spc.attack`+'3', `spc.defence`=`spc.defence`+'3', `".$wat."_up`=`".$wat."_up`+'3' WHERE `id`='".$_POST['pokemonid']."'");
    elseif($wat == "hp") mysql_query("UPDATE `pokemon_speler` SET `levenmax`=`levenmax`+'3', `".$wat."_up`=`".$wat."_up`+'3' WHERE `id`='".$_POST['pokemonid']."'");
    else mysql_query("UPDATE `pokemon_speler` SET `".$wat."`=`".$wat."`+'3', `".$wat."_up`=`".$wat."_up`+'3' WHERE `id`='".$_POST['pokemonid']."'");
    
    mysql_query("UPDATE `gebruikers_item` SET `".$_POST['item']."`=`".$_POST['item']."`-'1' WHERE `user_id`='".$_SESSION['id']."'");
    $finish = true;
    refresh(3, "index.php?page=items");
  }
}
?>
<center>
<table width="500" border="0">
<form method="post" name="usespcitem">
	<tr> 
		<td colspan="4"><? if($error) echo $error; else "&nbsp;"; ?></td>
	</tr>
	<tr> 
		<td width="50"><center><strong>&raquo;</strong></center></td>
		<td width="100"><strong>Pokemon:</strong></td>
		<td width="150"><strong>Naam:</strong></td>
		<td width="100" align="center"><strong>Level:</strong></td>
	</tr>
	<tr>
<?

//Pokemon laden van de gebruiker die hij opzak heeft
$poke = mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");

//Pokemons die hij opzak heeft weergeven  
for($teller=0; $pokemon = mysql_fetch_array($poke); $teller++){
	$pokemon = pokemonei($pokemon);
	$pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
	
  //Als pokemon geen baby is
	if($pokemon['ei'] != 1)
    	echo '<td><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'"/></center></td>';
  else
    echo '<td><center><input type="radio" id="niet'.$i.'" name="niet" disabled/></center></td></td>';

  echo '<td><center><img src="../'.$pokemon['animatie'].'" width="32" height="32"></center></td>
    <td>'.$pokemon['naam'].'</td>
    <td align="center">'.$pokemon['level'].'</td>
    </tr>
  ';
}

if(!$finish){
  ?>
    <tr> 
  		<td colspan="4"><input type="hidden" name="item" value="<? echo $_GET['name']; ?>">
  		<input type="submit" name="spcitem" value="Geven" class="button_mini" <? echo $geven; ?>>
  		<input type="submit" name="annuleer" value="Annuleren" class="button"></td>
  	</tr>
  <?
}
?>
</form>
</table>
</center>
