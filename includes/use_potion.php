<?
session_start();

include_once('config.php');
include_once('ingame.inc.php');
include_once('globaldefs.php');

$error = "<center><div style='padding-bottom:10px;'>Choose which pokemon you want to give ".$_GET['name']." to.</div></center>"; 
$gebruiker_item = mysql_fetch_array(mysql_query("SELECT * FROM `gebruikers_item` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
	header("Location: index.php?page=home");
	?>
  <script>  
  	parent.$.fn.colorbox.close();
  </script>
  <?
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-language" content="nl" />
    <meta name="description" content="" />
    <meta name="abstract" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="index, follow" />
    <meta name="copyright" content="(c) 2010" />
    <meta name="language" content="nl" />
    <title><?=GLOBALDEF_SITETITLE?></title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/box.css" />
  </head>
  
  <body>
  <?php
//Als er op de heal knop gedrukt word
if((isset($_POST['use'])) AND (isset($_POST['pokemonid']))){ 
  //Gegevens laden van de potion
  $itemgegevens = mysql_fetch_array(mysql_query("SELECT `kracht`, `naam`, `wat`, `apart`, `type1`, `type2`, `kracht2` FROM `items` WHERE `naam`='".$_POST['item']."'"));
  
  //Pokemon gegevens laden
  $pokemon = mysql_fetch_array(mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE pokemon_speler.id='".$_POST['pokemonid']."'"));
  $pokemon = pokemonei($pokemon);
  $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
  
  $life = False;
  $stauts = False;
  $finish = False;
  $newlife = $pokemon['leven'];
  $effect = $pokemon['effect'];
  
  //Is de potion niet apart?
  if($itemgegevens['apart'] == "nee") $life = True;
  elseif($itemgegevens['apart'] == "ja") $status = True;
  
  //Als er een gewone potion gebruikt is
  if($life){
  	if($pokemon['leven'] == $pokemon['levenmax'])
  	$error = '<div class="blue"><img src="../images/icons/blue.png"> '.$pokemon['naam'].' heeft al 100% leven.</div>';
    //Is het leven 0 dan heeft potions geen nut
    elseif($pokemon['leven'] > 0){         
      //Calculate new life
      $newlife = $pokemon['leven']+$itemgegevens['kracht'];
      //Check life
      if($newlife > $pokemon['levenmax']) $newlife = $pokemon['levenmax'];
      //Save new life
      mysql_query("UPDATE `pokemon_speler` SET `leven`='".$newlife."' WHERE `id`='".$_POST['pokemonid']."'");
      
      $finish = true;
    }
    else $error = '<div class="red"><img src="../images/icons/red.png" width="16" height="16" /> Je kunt '.$pokemon['naam'].' niet healen.</div>';

  }

  //Is er een aparte potion gebruikt?
  elseif($status){
    if($itemgegevens['naam'] == "Full heal")
	  $effect = '';
    elseif($pokemon['leven'] == 0){
      if($itemgegevens['naam'] == "Revive") $newlife = round($pokemon['levenmax']/2);
      elseif($itemgegevens['naam'] == "Max revive") $newlife = $pokemon['levenmax'];
      mysql_query("UPDATE `pokemon_speler` SET `leven`='".$newlife."', `effect`='".$effect."' WHERE `id`='".$_POST['pokemonid']."'");
      $finish = true;
    }
    else $error = '<div class="red"><img src="../images/icons/red.png" width="16" height="16" /> This only works on dead pokemon.</div>';

  }
  if($finish) mysql_query("UPDATE `gebruikers_item` SET `".$_POST['item']."`=`".$_POST['item']."`-'1' WHERE `user_id`='".$_SESSION['id']."'");

}

?>

<form method="post" name="useitem">
<center>
<table width="500">
   <tr> 
		<td colspan="4"><? if($error) echo $error; else "&nbsp;"; ?></td>
	</tr>
	<tr> 
		<td width="50"><center><strong>&raquo;</strong></center></td>
		<td width="100"><strong>Pokemon:</strong></td>
		<td width="50"><strong>Level:</strong></td>
		<td width="300"><strong>Health:</strong></td>
	</tr>
	<?
	//Pokemon laden van de gebruiker die hij opzak heeft
  $poke = mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
	
	//Pokemons die hij opzak heeft weergeven  
	for($teller=0; $pokemon = mysql_fetch_array($poke); $teller++){
    //Als leven niet 0 is en er word een Revive Of Max revive gebruikt, Dan is radio gedisabled
    if(($pokemon['leven'] != 0) AND (($_GET['name'] == "Revive") OR ($_GET['name'] == "Max revive")))
      $disabled = 'disabled';
    
    //Pagina includen dat berekend als het nog een pokemon ei is.
    $pokemon = pokemonei($pokemon);
    $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
    
    echo "<tr>";
    
    //Als pokemon geen baby is
    if($pokemon['ei'] != 1){
    	echo'
    	<td><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'" '.$disabled.'/></center></td>
    	<input type="hidden" name="teller" value="'.$teller.'">';               
    }
    else
    	echo '<td><center><input type="radio" id="niet'.$teller.'" name="niet" disabled/></center></td></td>';
    
    echo'<td><center><img src="../'.$pokemon['animatie'].'" width="32" height="32"></center></td>
         <td><center>'.$pokemon['level'].'</center></td>';
    
    //Als pokemon geen baby is
    if($pokemon['ei'] != 1){
    	echo '<td><div class="bar_red">
    				<div class="progress" style="width: '.$pokemon['levenprocent'].'%"></div>
    			  </div></td>';
    }
    else
    	echo '<td> HP: Inapplicable </td></tr>';
    
  }
  if(!$finish){
  	?>
     <tr> 
  	  <td colspan="4">
  	  <input type="hidden" name="item" value="<? echo $_GET['name']; ?>">
  	  <input type="submit" name="use" value="Give!" class="button_mini"></td>
     </tr>
  	<?
	}

  else{
    ?>
    <script>  
    	parent.$.fn.colorbox.close();
    </script>
    <?
  }
  ?>
</table>
</center>
</form>
</body>
</html>