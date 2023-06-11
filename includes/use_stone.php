<?
session_start();

include_once('config.php');
include_once('ingame.inc.php');

$error = "<center><div style='padding-bottom:10px;'>Kies welke Pokemon je de ".$_GET['name']." wil geven.</div></center>"; 
$gebruiker_item = mysql_fetch_array(mysql_query("SELECT * FROM `gebruikers_item` WHERE `user_id`='".$_SESSION['id']."'"));
if($gebruiker_item[$_GET['name']] <= 0){
	header("Location: index.php?page=items");
}

$button = true;

//Afbreken
if($_POST['annuleer']){ 
    header("Location: index.php?page=items");
}
//Als een pokemon moet evolueren met de steen
if(isset($_POST['zeker'])){
  //Gegevens laden van de des betreffende pokemon
  $pokemon = mysql_fetch_array(mysql_query("SELECT pokemon_wild.* ,pokemon_speler.*, karakters.* 
FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id 
INNER JOIN karakters ON pokemon_speler.karakter = karakters.karakter_naam 
WHERE pokemon_speler.id='".$_POST['pokemonid']."'"));
  //Gegevens halen uit de levelen tabel
  $levelensql = mysql_query("SELECT nieuw_id FROM `levelen` WHERE `id`='".$_POST['levelenid']."'");
  $levelen = mysql_fetch_array($levelensql);
  if(empty($_POST['pokemonid']))
    $error = 'FOUT 2!<br /> Code: '.$_POST['pokemonid'].'<br />Like everything about to';
  elseif(mysql_num_rows($levelensql) == 1){
    $update = mysql_fetch_array(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='".$levelen['nieuw_id']."'"));
	
    //Formule Stats = int((int(int(A*2+B+int(C/4))*D/100)+5)*E)    
    $attackstat     = round(((((($update['attack_base']*2+$pokemon['attack_iv']+floor($pokemon['attack_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['attack_up'])*$pokemon['attack_add']);
    $defencestat    = round(((((($update['defence_base']*2+$pokemon['defence_iv']+floor($pokemon['defence_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['defence_up'])*$pokemon['defence_add']);
    $speedstat      = round(((((($update['speed_base']*2+$pokemon['speed_iv']+floor($pokemon['speed_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['speed_up'])*$pokemon['speed_add']);
    $spcattackstat  = round(((((($update['spc.attack_base']*2+$pokemon['spc.attack_iv']+floor($pokemon['spc.attack_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['spc_up'])*$pokemon['spc.attack_add']);
    $spcdefencestat = round(((((($update['spc.defence_base']*2+$pokemon['spc.defence_iv']+floor($pokemon['spc.defence_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['spc_up'])*$pokemon['spc.defence_add']);
    $hpstat         = round((((($update['hp_base']*2+$pokemon['hp_iv']+floor($pokemon['hp_ev']/4))*$pokemon['level']/100)+$pokemon['level'])+10)+$pokemon['hp_up']);
      
    //Pokemon gegevens en nieuwe Stats opslaan
    mysql_query("UPDATE `pokemon_speler` SET `wild_id`='".$levelen['nieuw_id']."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokemon['id']."'");
    //Pokemon opslaan als in bezit
    update_pokedex($update['wild_id'],$pokemon['wild_id'],'evo');
    //Stone weg
    mysql_query("UPDATE `gebruikers_item` SET `".$_POST['item']."`=`".$_POST['item']."`-'1' WHERE `user_id`='".$_SESSION['id']."'");
    //Post leeg maken.
    unset($_POST['zeker']);
    
    $error = '<div class="green"><img src="images/icons/green.png"> Gefeliciteerd, je <strong>'.$pokemon['naam'].'</strong> is geëvolueerd in een <strong>'.$update['naam'].'</strong> met een '.$_POST['item'].'!</div>';
  }
  else{
    $error = 'FOUT 1!<br /> Code: '.$_POST['levelenid'].'<br />Like everything about to';
  }
  ?>
  <center>
  <table width="500" border="0">
  	<tr>
  		<td colspan="3"><? if($error) echo $error; else echo "&nbsp"; ?></td>
  	</tr>
  	<tr>
  		<td width="200"><center><img src="images/<?php if($pokemon['shiny'] == 1) echo 'shiny'; else echo 'pokemon'; ?>/<? echo $pokemon['wild_id']; ?>.gif" height="96" /></center></td>
  		<td width="86"><center><img src="images/icons/pijl_rechts.png" /></center></td>
  		<td width="200"><center><img src="images/<?php if($update['shiny'] == 1) echo 'shiny'; else echo 'pokemon'; ?>/<? echo $update['wild_id']; ?>.gif" height="96" /></center></td>
  	</tr>
  </table>
  </center>
  <?
}
else{
	if(isset($_POST['evolve'])){
  	list ($pokemonid, $levelenid, $pokemonnaam, $wildid) = split ('[/]', $_POST['pokemonid']);
  	if(empty($pokemonid))
		  echo '<div class="red"><img src="images/icons/red.png" width="16" height="16" /> Je hebt geen pokemon aangevinkt. Kies welke pokemon je een '.$_POST['item'].' wilt geven.';
		elseif(empty($levelenid))
		  echo '<div class="red"><img src="images/icons/red.png" width="16" height="16" /> FOUT!<br /> Code: '.$_POST['pokemonid'].'<br />Graag melden aan darkshifty';
  	elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `levelen` WHERE `wild_id`='".$wildid."' AND `wat`='evo' AND `stone`='".$_POST['item']."'")) == 0)
		  echo '<div class="red"><img src="images/icons/red.png" width="16" height="16" /> Je kunt '.$pokemonnaam.' geen '.$_POST['item'].' geven.';  
  	elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `levelen` WHERE `id`='".$levelenid."'")) == 0)
		  echo '<div class="red"><img src="images/icons/red.png" width="16" height="16" /> Fout!<br /> Code: '.$_POST['pokemonid'].'<br />Graag melden aan darkshifty';
   	else{
			echo '<center><div class="blue"><img src="images/icons/blue.png" width="16" height="16" /> Weet je zeker dat je '.$pokemonnaam.' wil laten evolueren?<br />';
			echo '<form method="post">
        <input type="hidden" Value="'.$_POST['item'].'" name="item">
        <input type="hidden" Value="'.$pokemonid.'" name="pokemonid">
        <input type="hidden" Value="'.$levelenid.'" name="levelenid">   
				<input type="submit" Value="Ja" name="zeker" class="button"> | <input type="submit" Value="Nee" name="nee" class="button">
				</form></div></center>';
    }
  }
  else{
  ?>

    <form method="post" name="useitem">
    <center>
    <table width="500" border="0">
    	<tr> 
    		<td colspan="5"><? if($error) echo $error; else echo "&nbsp"; ?></td>
    	</tr>
    	<tr> 
    		<td width="50"><center><strong>&raquo;</strong></center></td>
    		<td width="100"><strong>Pokemon:</strong></td>
    		<td width="150"><strong>Naam:</strong></td>
    		<td width="100" align="center"><strong>Level:</strong></td>
    		<td width="100" align="center"><strong>Kan evolueren:</strong></td>
    	</tr>
    	<tr>
    <?
    //Pokemon laden van de gebruiker die hij opzak heeft
    $poke = mysql_query("SELECT pokemon_wild.* ,pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
    
    //Pokemons die hij opzak heeft weergeven  
    for($teller=0; $pokemon = mysql_fetch_array($poke); $teller++){
      $kan = "<img src='images/icons/red.png' alt='Kan niet'>";
      $disabled = 'disabled';   
      //Als er een result is kan pokemon evolueren.
      $stoneevolvesql = mysql_query("SELECT `id`, `stone`, `nieuw_id` FROM `levelen` WHERE `wild_id`='".$pokemon['wild_id']."' AND `stone`='".$_GET['name']."'");
      $stoneevolve = mysql_fetch_array($stoneevolvesql);
      
      //Heeft de stone werking?
      if(mysql_num_rows($stoneevolvesql) >= 1){
      	$kan = "<img src='images/icons/green.png' alt='Kan wel'>";
      	$disabled = '';
      }
    
      //Als pokemon geen baby is
      if($pokemon['ei'] != 1){
        echo '
          <td><center><input type="hidden" name="levelenid" value="'.$stoneevolve['id'].'">
          <input type="radio" name="pokemonid" value="'.$pokemon['id'].'/'.$stoneevolve['id'].'/'.$pokemon['naam'].'/'.$pokemon['wild_id'].'" '.$disabled.'/>
          <input type="hidden" name="pokemonnaam" value="'.$pokemon['naam'].'"></center></td>
        ';             
      }
      else
        echo '<td><center><input type="radio" id="niet'.$i.'" name="niet" disabled/></center></td></td>';
      
      $pokemon = pokemonei($pokemon);
      $pokemon['naam_goed'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
      
      echo '
        <td><center><img src="../'.$pokemon['animatie'].'" width="32" height="32"></center></td>
        <td>'.$pokemon['naam_goed'].'</td>
        <td align="center">'.$pokemon['level'].'</td>
      ';
      
      //Als pokemon geen baby is
      if($pokemon['ei'] != 1) echo '<td align="right">'.$kan.'</td>';
      else echo '<td align="right">Error</td>';
      	
      echo '</tr>';
    }
    
    if($button){
      ?>
      <tr> 
        <td colspan="5"><input type="hidden" name="item" value="<? echo $_GET['name']; ?>">
        <input type="submit" name="evolve" value="Geven" class="button_mini">
        <input type="submit" name="annuleer" value="Annuleren" class="button"></td>
      </tr>
      <?
    }
    ?>
    </table>
    </center>
    </form>
    <?
    }
  }
?>