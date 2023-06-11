<?
session_start();

include_once('config.php');
include_once('ingame.inc.php');
include_once('globaldefs.php');

if($_GET['pokemonid'] < 0){
	header("Location: index.php?page=home");
?>
  <script>  
  	parent.$.fn.colorbox.close();
  </script>
  <?
}

#Als er een result is kan pokemon evolueren.


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns=https:://www.w3.org/1999/xhtml">
  <head>
    <title><?=GLOBALDEF_SITETITLE?></title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/box.css" />
  </head>
  <body>
  
  <?php	
  		if($error) echo $error;
		echo "<center><div style='padding-bottom:10px;'>The available moves are:</div></center>";
		
		$check = mysql_fetch_assoc(mysql_query("SELECT type1, type2 FROM tmhm WHERE `naam`='".$_GET['name']."'"));
		#Pokemon laden van de gebruiker die hij opzak heeft
		$poke = mysql_query("SELECT pokemon_wild.wild_id, pokemon_wild.type1, pokemon_wild.type2, pokemon_wild.naam, pokemon_speler.id, pokemon_speler.level, pokemon_speler.shiny, pokemon_speler.user_id FROM pokemon_wild 
							INNER JOIN pokemon_speler 
							ON pokemon_speler.wild_id = pokemon_wild.wild_id 
							WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
  ?>
    
<form method="post">
<center>
<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr> 
		<td width="50" class="top_first_td"><center><strong>Choose</strong></center></td>
		<td width="100" class="top_td"><strong><center>Level</center></strong></td>
		<td width="150" class="top_td"><strong>Move</strong></td>
		<td width="50" class="top_td"><strong>Power</strong></td>
		<td width="50" class="top_td"><strong>Accuracy</strong></td>
		<td width="50" align="center" class="top_td"><strong>Price</strong></td>
	</tr>
<?

if(empty($_GET['pokemonid'])) $error =  '<div class="red">Error.</div>';

$query1 = mysql_query("SELECT wild_id, level FROM pokemon_speler WHERE id=".$_GET['pokemonid']." ");
$get_id = mysql_fetch_assoc($query1);

$query2 = mysql_query("SELECT level, aanval FROM levelen WHERE wild_id=".$get_id['wild_id']." AND wat='att' AND level<=".$get_id['level']." ORDER BY level ASC ");

#Pokemons die hij opzak heeft weergeven

while ($move = mysql_fetch_assoc($query2)) {
  
  echo '<tr>';
  
    echo '<td class="normal_first_td"><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'" /></center></td>';             

  echo '<td class="normal_td"><center>'.$move['level'].'</center></td>
	<td class="normal_td">'.$move['aanval'].'</td>
    	<td class="normal_td"><center>'.$move['level'].'</center></td>
	<td class="normal_td"><center>'.$move['level'].'</center></td>
    	<td class="normal_td"><center>'.$move['level'].'</center></td>';

  echo '</tr>';
  }

  ?>
  <tr> 
    <td colspan="5"><center> <br /> <input type="submit" name="choose" value="Choose" class="button_mini"></center></td>
  </tr>
</table>
</center>
</form>
</body></html>