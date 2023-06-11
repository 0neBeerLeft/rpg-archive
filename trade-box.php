<?
#Sessies aan zetten
session_start(); 

$page = 'trade-box';

#config laden
include_once("includes/config.php");
include_once('includes/ingame.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<?
$select = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank
										FROM pokemon_speler
										INNER JOIN pokemon_wild
										ON pokemon_speler.wild_id = pokemon_wild.wild_id
										INNER JOIN gebruikers ON
										pokemon_speler.user_id = gebruikers.user_id
										WHERE pokemon_speler.id='".$_GET['id']."'"));
										
$select2sql = mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank
										FROM pokemon_speler
										INNER JOIN pokemon_wild
										ON pokemon_speler.wild_id = pokemon_wild.wild_id
										INNER JOIN gebruikers ON
										pokemon_speler.user_id = gebruikers.user_id
										WHERE pokemon_speler.user_id='".$_SESSION['id']."'");

#user rank gegevens laden
$you = mysql_fetch_assoc(mysql_query("SELECT rank FROM gebruikers WHERE user_id = '".$_SESSION['id']."'"));
$maxlevel = $you['rank']*5;	
									
#geen beginpokemon
if($select['gehecht'] == 1){
	echo '<div class="red">Starter Pokémon kan niet geruild worden!</div>';
}
#Pokemon is van jou
elseif($select['user_id'] == $_SESSION['id']){
  echo '<div class="red">Je kan helaas niets ruilen tegen deze Pokémon.</div>';
}

else{
#Ruilen
if(isset($_POST['ruil'])){
	$select3 = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank
										FROM pokemon_speler
										INNER JOIN pokemon_wild
										ON pokemon_speler.wild_id = pokemon_wild.wild_id
										INNER JOIN gebruikers ON
										pokemon_speler.user_id = gebruikers.user_id
										WHERE pokemon_speler.id='".$_POST['pokemonid']."'"));
	$aantal = mysql_num_rows(mysql_query("SELECT pokemonid_bieder FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."'"));
	$tradeid = mysql_num_rows(mysql_query("SELECT pokemonid FROM trade_center WHERE pokemonid ='".$_GET['id']."'"));
	#geen beginpokemon
	if($select3['gehecht'] == 1)
		echo '<div class="red">Starter Pokémon kan niet geruild worden!</div>';
		
	#niet je eigen pokemon
	elseif($select3['user_id'] != $_SESSION['id'])
		echo '<div class="red">Je kan helaas niets ruilen tegen deze Pokémon.</div>';
	#aantal keren geboden
	elseif(mysql_num_rows(mysql_query("SELECT pokemonid_bieder FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."'")) >= 5)
		echo '<div class="red">Je kan niet meer dan 5 Pokémon tegelijk wisselen.</div>';
	#niet zelfde pokemon bieden
	elseif(mysql_num_rows(mysql_query("SELECT pokemonid, pokemonid_bieder FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."' AND pokemonid ='".$select['id']."'")) >= 1)
		echo '<div class="red">Fout.</div>';
	#kijk of pokemon nog op tradelist staat
	elseif($tradeid = mysql_num_rows(mysql_query("SELECT pokemonid FROM trade_center WHERE pokemonid ='".$_GET['id']."'" )) < 1)
		echo '<div class="red">Deze Pokémon bestaat niet.</div>';
	
	else{
		mysql_query("UPDATE trade_center SET biedingen = biedingen +1 WHERE pokemonid = '".$select['id']."'");
		mysql_query("INSERT INTO trade_biedingen (bieding_id, pokemonid, user_id, pokemonid_bieder, bieder_id)
    		  VALUES ('NULL()','".$select['id']."', '".$select['user_id']."', '".$_POST['pokemonid']."', '".$_SESSION['id']."')");
        $offer = mysql_fetch_assoc(mysql_query("SELECT * FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."'"));

        $select2sqlname = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank
										FROM pokemon_speler
										INNER JOIN pokemon_wild
										ON pokemon_speler.wild_id = pokemon_wild.wild_id
										INNER JOIN gebruikers ON
										pokemon_speler.user_id = gebruikers.user_id
										WHERE pokemon_speler.user_id='".$_SESSION['id']."'
										AND pokemon_speler.id = '".$_POST['pokemonid']."'"));

			#Melding geven aan de ruiler
			$userid = mysql_fetch_array(mysql_query("SELECT user_id FROM trade_biedingen WHERE pokemonid_bieder ='".$_POST['pokemonid']."'" ));
			$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> '.$_SESSION['naam'].' heeft een '.$select2sqlname['naam'].' van level '.$select2sqlname['level'].' geboden op '.$select['naam'].'. <a href="?page=trade-box&action=accept&acceptid='.$offer['bieding_id'].'">Accepteren</a> | <a href="?page=trade-box&action=decline&declineid='.$offer['bieding_id'].'">Weigeren</a>';
			$result = mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '".$userid['user_id']."', '".$event."', '0')");

	
		echo '<div class="green">Je hebt met succes een bod ingedient.</div>';
		}

}
if(isset($_GET['action'])){
    if($_GET['action'] == "accept") {
        $acceptid = mysql_escape_string($_GET['acceptid']);
        $offer = mysql_fetch_assoc(mysql_query("SELECT * FROM trade_biedingen WHERE bieding_id = '" . $acceptid . "'"));
		if($offer) {
			/*array(5) {
                ["bieding_id"]=>
            string(4) "1572"
                ["pokemonid"]=>
            string(4) "7135"
                ["user_id"]=>
            string(5) "17007"
                ["pokemonid_bieder"]=>
            string(4) "7309"
                ["bieder_id"]=>
            string(5) "16900"
            }*/
			//$pokemon['trade'] = "1.5";
			//$levelnieuw = +1;
			//levelgroei($levelnieuw, $pokemon['trade']);

			#accepted offer
			$event = "Je bod is geaccepteerd";
			mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '" . $offer['bieder_id'] . "', '" . $event . "', '0')");

			#delete offer
			mysql_query("DELETE FROM trade_biedingen WHERE bieding_id='" . $offer['bieding_id'] . "'");
			mysql_query("DELETE FROM trade_center WHERE pokemonid='" . $offer['pokemonid'] . "'");

			#exchange offer
			mysql_query("UPDATE pokemon_speler SET user_id = '" . $offer['bieder_id'] . "',opzak='nee' WHERE id = '" . $offer['pokemonid'] . "'");
			mysql_query("UPDATE pokemon_speler SET user_id = '" . $offer['user_id'] . "',opzak='nee' WHERE id = '" . $offer['pokemonid_bieder'] . "'");

			$selecttradeoffer = mysql_fetch_assoc(mysql_query("SELECT * FROM pokemon_speler WHERE id = '" . $offer['pokemonid_bieder'] . "'"));
			$checkifevolve = mysql_query("SELECT * FROM levelen WHERE wild_id = '" . $selecttradeoffer['wild_id'] . "'");
			$update = mysql_fetch_assoc(mysql_query("SELECT pw.*, ps.* FROM pokemon_wild AS pw INNER JOIN pokemon_speler ps ON pw.wild_id = ps.wild_id WHERE id = '" . $offer['pokemonid_bieder'] . "'"));

			#check if pokemon needs to evolve
			$evolvepokemon = false;
			while ($evolve = mysql_fetch_assoc($checkifevolve)) {
				if ($evolve['trade'] == true) {
					$evolvepokemon = true;
				}
			}
			#set pokemon to evolve
			if ($evolvepokemon) {
				mysql_query("UPDATE pokemon_speler SET trade=1.5 WHERE id = '" . $offer['pokemonid_bieder'] . "'");
			}


			$selecttradeoffer2 = mysql_fetch_assoc(mysql_query("SELECT * FROM pokemon_speler WHERE id = '" . $offer['pokemonid'] . "'"));
			$checkifevolve2 = mysql_query("SELECT * FROM levelen WHERE wild_id = '" . $selecttradeoffer2['wild_id'] . "'");
			$update2 = mysql_fetch_assoc(mysql_query("SELECT pw.*, ps.* FROM pokemon_wild AS pw INNER JOIN pokemon_speler ps ON pw.wild_id = ps.wild_id WHERE id = '" . $offer['pokemonid'] . "'"));

			#check if pokemon needs to evolve
			$evolvepokemon2 = false;
			while ($evolve2 = mysql_fetch_assoc($checkifevolve2)) {
				if ($evolve2['trade'] == true) {
					$evolvepokemon2 = true;
				}
			}
			#set pokemon to evolve
			if ($evolvepokemon2) {
				mysql_query("UPDATE pokemon_speler SET trade=1.5 WHERE id = '" . $offer['pokemonid'] . "'");
			}
			echo "Bod geaccepteerd.";
		} else {
			echo "Bod bestaat niet meer.";
		}
    }
    if($_GET['action'] == "decline") {
        $declineid = mysql_escape_string($_GET['declineid']);
        $offer = mysql_fetch_assoc(mysql_query("SELECT * FROM trade_biedingen WHERE bieding_id = '" . $declineid . "'"));
		
		if($offer) {
			$event = "Je bod is afgewezen";
			mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '" . $offer['bieder_id'] . "', '" . $event . "', '0')");

			mysql_query("DELETE FROM trade_biedingen WHERE bieding_id='" . $declineid . "'");
			echo "Bod afgewezen.";
		}else{
			echo "Bod bestaat niet meer.";
		}
    }

}
if (!$_GET['action']) {
	?>
	<form method="post" name="form">
		<? echo 'Ruil hier een Pokémon naar keuze tegen een <b>' . $select['naam'] . '</b> met level <b>' . $select['level'] . '</b></b></br>' ?>



		<select name="pokemonid" class="text_select" style="float:none; margin-right:2px;">
			<?php
			#Pokemons opzak weergeven op het scherm
			if (!empty($select2sql)) {
				while ($select2 = mysql_fetch_assoc($select2sql)) {

					echo '<option value="' . $select2['id'] . '">' . $select2['naam'] . '</option>';
				}
			} else {
				echo "<option value=''>--geen--</option>";
			}
			?>
		</select>
		<button type="submit" name="ruil" class="button">Ruilen</button>


	</form>
<?
}
#else afsluiten
}
?>
</body>
</html>