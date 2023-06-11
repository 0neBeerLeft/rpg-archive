<?
#Security load
include("includes/security.php");
#Load language
$page = 'trade-add';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if($gebruiker['rank'] <= 3) header("Location: index.php?page=home");

?>
<script type="text/javascript" src="js/jquery.js""></script>
<script type="text/javascript" src="javascripts/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox").colorbox({width:"500", height:"200", iframe:true});
					
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
		$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox2").colorbox({width:"800", height:"600", iframe:true});
					
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
		$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
	});
</script>

<?

$tradesql = mysql_query("SELECT trade_center.*, pokemon_speler.wild_id, pokemon_speler.shiny FROM trade_center INNER JOIN pokemon_speler ON trade_center.pokemonid = pokemon_speler.id WHERE trade_center.userid = '".$_SESSION['id']."'");

#op swap zetten
if(isset($_POST['zetruil'])){
	$pokruil = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.naam, pokemon_speler.id,pokemon_speler.user_id, pokemon_speler.opzak, pokemon_speler.gehecht, pokemon_speler.level FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE id = '".$_POST['pokemonid']."'")) OR DIE(mysql_error());
	$tradequery = mysql_num_rows(mysql_query("SELECT userid FROM trade_center WHERE userid = '".$_SESSION['id']."'"));
	$tradecount = 2;
	if($gebruiker['premiumaccount'] >= 1) $tradecount = 4;
	
	$count = mysql_num_rows(mysql_query("SELECT `trade_id` FROM `trade_center` WHERE `userid`='".$_SESSION['id']."'"));
	if($count < $tradecount){ 
	
	if($pokruil['user_id'] != $_SESSION['id'])
    $error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red"> Kies een Pokémon. </font></p></div>';
	#beginpokemon
	elseif($pokruil['gehecht'] == 1)
	$error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red"> Starter-Pokémon kunnen niet verhandeld worden. </font></p></div>';
	else{
    mysql_data_seek($pokemon_sql, 0);
    $i = 0;
    while($pokemon = mysql_fetch_assoc($pokemon_sql)){
      if($pokemon['id'] == $_POST['pokemonid']){
			mysql_query("UPDATE pokemon_speler SET `opzak`='trc', `opzak_nummer`='' WHERE id = '".$_POST['pokemonid']."'");
			mysql_query("INSERT INTO trade_center (trade_id, pokemonid, userid, pokemonnaam, level)
    		  VALUES ('NULL()','".$pokruil['id']."', '".$_SESSION['id']."', '".$pokruil['naam']."', '".$pokruil['level']."')");
			
			$selectsql = mysql_query("SELECT `id`,`opzak_nummer` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND `id`!='".$_POST['pokemonid']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
                  for($i = 1; $select1 = mysql_fetch_assoc($selectsql); $i++){
                    #Alle opzak_nummers ééntje lager maken van alle pokemons die over blijven
                    mysql_query("UPDATE `pokemon_speler` SET `opzak_nummer`='".$i."' WHERE `id`='".$select1['id']."'");
                 }
                $gebruiker['in_hand'] -= 1;
	  
    		
    		
			  
    	$error = '<div class="green"><p><img src="images/icons/alert_green.png" alt="error" /><font color="green"> Je Pokémon is succesvol aan de Tradecenter toegevoegd. </font></p></div>';
		}
	}	
	}
}else{
  echo '<div class="blue">Je kan geen andere Pokémon uitwisselen.</div>';
}
}
#Terugnemen
if(isset($_POST['haalop'])){
  $select = mysql_fetch_assoc(mysql_query("SELECT * FROM `trade_center` WHERE `pokemonid`='".$_POST['pokemonid']."'"));
	$hoeveelinhand = $gebruiker['in_hand'] + 1;
	if($_SESSION['id'] != $select['userid'])
    $error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red"> Fout. </font></p></div>';
	elseif($hoeveelinhand == 7)
		$error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red"> Je kan niet meer dan 6 Pokémon bij je dragen. </font></p></div>';
	else{
  	mysql_query("UPDATE pokemon_speler SET `opzak`='ja', `opzak_nummer`='".$hoeveelinhand."' WHERE id = '".$_POST['pokemonid']."'");
  	mysql_query("DELETE FROM trade_center WHERE pokemonid = '".$_POST['pokemonid']."'");
	mysql_query("DELETE FROM trade_biedingen WHERE pokemonid = '".$_POST['pokemonid']."'");
	mysql_query("DELETE FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."'");
  	
	$error = '<div class="green"><p><img src="images/icons/alert_green.png" alt="error" /><font color="green"> Ruil afgebroken. </font></p></div>';
	}
}

?>
<div>
<div>
	<div>
		<h2>Pokémon Tradecenter</h2><hr>
		<p>Hier kun je je Pokémon ruilen met Pokémon van andere spelers. <br />
Het maximaal te ruilen Pokémon is 2 stuks, Premium leden kunnen er 4 ruilen.
		<br /><br />
		<?php if($error != '') echo $error; ?></p>
	</div>
	
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td colspan="3" class="top_first_td"><p><b><?php echo $txt['alert_swap']; ?></b></p></td>
  </tr>
 </table>
 <form method="post">
<table width="580" cellspacing="5" cellpadding="0" width="100%">

  <tr>
		<td width="100" rowspan="100" valign="top"><img src="images/tradeball.png" width="150" title="<?php echo $profiel['clan_type']; ?>"/></td>
        <td width="300"><p><b>Kies een Pokémon die je wil ruilen.</b></p></td>
  </tr>
  <tr>
		<td width="300"><p><?php echo $txt['alert_choise_pokemon']; ?></p></td>
  </tr>
  <td>
            
            <select name="pokemonid" class="text_select" style="float:none; margin-right:2px;">
            <?php
                #Pokemons opzak weergeven op het scherm
                if($gebruiker['in_hand'] > 0) mysql_data_seek($pokemon_sql, 0);
                mysql_data_seek($pokemon_sql, 0);
                while($pokemon = mysql_fetch_assoc($pokemon_sql)){
                    echo '<option value="'.$pokemon['id'].'">'.$pokemon['naam'].'</option>';
                }
				mysql_data_seek($pokemon_sql, 0);
            ?></select>
            <button type="submit" name="zetruil" class="button">Toevoegen</button>
  </td>
  
 </table>
 </form>
 <hr>
 <p><b><?php echo $txt['alert_pokemon']; ?></b></p>
            <table width="100%" cellpadding="0" cellspacing="0">
            	<tr>
                	<td width="80" class="top_first_td"><p><?php echo '#'; ?></p></td>
                    <td width="110" class="top_td"><p><?php echo 'Naam'; ?></p></td>
                    <td width="110" class="top_td"><p><?php echo 'Level'; ?></p></td>
                    <td colspan="2" align="left" class="top_td"><p>Actie</p></td>
               </tr>
			   <?php while($trade = mysql_fetch_assoc($tradesql)){
      				   $aantalbiedingen = mysql_num_rows(mysql_query("SELECT pokemonid FROM trade_biedingen WHERE pokemonid = '".$trade['pokemonid']."'"));
					   $map = 'pokemon';
      				   if($trade['shiny'] == '1') $map = 'shiny';
      						echo'
      							<tr>
      								<td class="normal_first_td"><img src="images/'.$map.'/icon/'.$trade['wild_id'].'.gif"></td>
      								<td class="normal_td"><p>'.$trade['pokemonnaam'].'</p></td>
      								<td class="normal_td"><p>'.$trade['level'].'</p></td>
      								<td class="normal_td"><form method="post"><input type="hidden" name="pokemonid" value="'.$trade['pokemonid'].'">
      									<button type="submit" name="haalop" class="button">Intrekken</button></form></td>
									<td class="normal_td" align="right" >'.$aantalbiedingen.' <a target="_BLANK" href="trade-box.php?id='.$trade['pokemonid'].'" class="colorbox2" title="'.$trade['pokemonnaam'].'"><img src="images/icons/sell.gif"></a></td>
      						   </tr>';
      					   }
      			   ?>
			</table>
	</div>
	</div>