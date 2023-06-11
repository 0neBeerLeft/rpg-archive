<?
//session_start();

//include_once('config.php');
include_once('ingame.inc.php');

$error = "<center><div style='padding-bottom:10px;'>Kies hier welke Pokemon je een ".$_GET['name']." wil geven.</div></center>";
$gebruiker_item = mysql_fetch_assoc(mysql_query("SELECT * FROM `gebruikers_item` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
	header("Location: index.php?page=home");
}
?>
  <?
    if($_POST['annuleer']){ 
        header("Location: index.php?page=items");
    }
  if((isset($_POST['spcitem'])) AND(isset($_POST['pokemonid']))){
    //Pokemon gegevens laden
    $pokemon = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE pokemon_speler.id='".$_POST['pokemonid']."'"));
    
    if($pokemon['level'] < 100){
      //informatie van level
      $levelnieuw = $pokemon['level']+1;
      
      //Script aanroepen dat nieuwe stats berekent
      nieuwestats($pokemon,$levelnieuw,$pokemon['expnodig']);
      
      //Script aanroepen dat berekent als pokemon evolueert of een aanval leert
      $toestemming = levelgroei($levelnieuw,$pokemon);
      
      //Gebeurtenis maken.
	  $gebruiker = mysql_fetch_assoc(mysql_query("SELECT land FROM gebruikers WHERE user_id = '".$_SESSION['id']."'"));
	  
      $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
      $pokemonnaam = htmlspecialchars($pokemon['naam'], ENT_QUOTES);
	  
		#Event taal pack includen
		$eventlanguage = GetEventLanguage($gebruiker['land']);
		include('../language/events/language-events-'.$eventlanguage.'.php');
	  
      	$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> '.$pokemonnaam.' '.$txt['event_is_level_up'];
		
		#Melding geven aan de uitdager
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
		VALUES (NULL, NOW(), '".$_SESSION['id']."', '".$event."', '0')");
      
      #Item weg
      mysql_query("UPDATE `gebruikers_item` SET `".$_POST['item']."`=`".$_POST['item']."`-'1' WHERE `user_id`='".$_SESSION['id']."'");
      $finish = true;
        refresh(3, "index.php?page=items");
    }
    else
    	$error = $pokemon['naam']." is level 100.";
  }
  ?>
  <center>
  <table width="500" border="0">
  <form method="post" name="userc">
  	<tr> 
  		<td colspan="4"><? if($error) echo $error; else "&nbsp;"; ?></td>
  	</tr>
  	<tr> 
  		<td width="50"><center><strong>&raquo;</strong></center></td>
  		<td width="100"><strong>Pokemon:</strong></td>
  		<td width="150"><strong>Naam:</strong></td>
  		<td width="100"><strong>Level:</strong></td>
  	</tr>
  	<tr>
  <?
  
  //Pokemon laden van de gebruiker die hij opzak heeft
  $poke = mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");

  //Pokemons die hij opzak heeft weergeven  
  for($teller=0; $pokemon = mysql_fetch_assoc($poke); $teller++){
  	$pokemon = pokemonei($pokemon);
  	$pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
  	
  	$disabled = '';
  
  	if($pokemon['level'] >= 100) $disabled = 'disabled';
  
    //Als pokemon geen baby is
  	if(($pokemon['baby'] != "Ja") OR ($pokemon['level'] < 100))
      	echo '<td><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'" '.$disabled.'/></center></td>';
    else
      echo '<td><center><input type="radio" id="niet'.$i.'" name="niet" disabled/></center></td></td>';
  
    
    echo '
      <td><center><img src="../'.$pokemon['animatie'].'" width="32" height="32"></center></td>
      <td>'.$pokemon['naam'].'</td>
      <td>'.$pokemon['level'].'</td>
      </tr>';
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