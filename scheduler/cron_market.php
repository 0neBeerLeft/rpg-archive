<?php
include_once ('cronConfig.php');

$sql = mysql_query("SELECT markt.id, pokemon_wild.wereld
				FROM markt
				INNER JOIN pokemon_wild
				ON markt.pokemonid = pokemon_wild.wild_id
				WHERE markt.soort = 'pokemon'
				AND markt.beschikbaar = '0'");

while($select = mysql_fetch_assoc($sql)){

#New pokemonid genereren op basis van wereld
#Info ophalen van nieuwe pokemon
$newinfo = mysql_fetch_assoc(mysql_query("SELECT wild_id, naam, type1, zeldzaamheid FROM pokemon_wild WHERE wereld = '".$select['wereld']."' AND evolutie = '1' ORDER BY rand() LIMIT 1"));

#Prijs en omschrijving van alle talen genereren
if($newinfo['zeldzaamheid'] == 1){
	$silver_price = rand(1250,3500);
	$gold_price = 0;
	$omschrijving_nl = 'Een ei dat veel voorkomt. Dit is een '.$newinfo['type1'].' type pokemon ei.';
	$omschrijving_en = 'A not rare egg. This is a '.$newinfo['type1'].' type pokemon egg.';
	$omschrijving_es = 'A not rare egg. This is a '.$newinfo['type1'].' type pokemon egg.';
	$omschrijving_de = 'A not rare egg. This is a '.$newinfo['type1'].' type pokemon egg.';
	$omschrijving_pl = 'A not rare egg. This is a '.$newinfo['type1'].' type pokemon egg.';
}
elseif($newinfo['zeldzaamheid'] == 2){
	$silver_price = rand(4000, 9999);
	$gold_price = 0;
	$omschrijving_nl = 'Een ei dat een beetje zeldzaam is. Het lijkt alsof er een '.$newinfo['type1'].' type pokemon in dit ei zit.';
	$omschrijving_en = 'This egg is a bit rare. It looks like there is a '.$newinfo['type1'].' type pokemon in this egg.';
	$omschrijving_es = 'This egg is a bit rare. It looks like there is a '.$newinfo['type1'].' type pokemon in this egg.';
	$omschrijving_de = 'This egg is a bit rare. It looks like there is a '.$newinfo['type1'].' type pokemon in this egg.';
	$omschrijving_pl = 'This egg is a bit rare. It looks like there is a '.$newinfo['type1'].' type pokemon in this egg.';
}
else{
	$silver_price = 0;
	$gold_price = rand(300, 800);
	$omschrijving_nl = 'Een zeldzaam ei. Deskundigen denken dat dit een ei is van een '.$newinfo['type1'].' type pokemon.';
	$omschrijving_en = 'A very rare egg. Scientist think that this is an egg of a '.$newinfo['type1'].' type pokemon.';
	$omschrijving_es = 'A very rare egg. Scientist think that this is an egg of a '.$newinfo['type1'].' type pokemon.';
	$omschrijving_de = 'A very rare egg. Scientist think that this is an egg of a '.$newinfo['type1'].' type pokemon.';
	$omschrijving_pl = 'A very rare egg. Scientist think that this is an egg of a '.$newinfo['type1'].' type pokemon.';
}

#Product opslaan in database
mysql_query("UPDATE markt SET beschikbaar = '1', pokemonid = '".$newinfo['wild_id']."', naam = '".$newinfo['naam']."', silver = '".$silver_price."', gold = '".$gold_price."', omschrijving_nl = '".$omschrijving_nl."', omschrijving_en = '".$omschrijving_en."', omschrijving_es = '".$omschrijving_es."', omschrijving_de = '".$omschrijving_de."', omschrijving_pl = '".$omschrijving_pl."' WHERE id = '".$select['id']."'");

}

#Tijd opslaan van wanneer deze file is uitgevoerd
$tijd = date("Y-m-d H:i:s");
mysql_query("UPDATE `cron` SET `tijd`='".$tijd."' WHERE `soort`='markt'");
?>