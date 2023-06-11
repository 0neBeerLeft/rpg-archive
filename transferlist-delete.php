<?
#Verbinding met de database maken
include('includes/config.php');

$pokemonid  = $_GET["pokemonid"];
$paginaid   = $_GET["paginaid"];

#Verwijderen uit transferlijst tabel
mysql_query("DELETE FROM `transferlijst` WHERE `id`='".$paginaid."'");

#pokemon opslaan als zijne van transferlijst
mysql_query("UPDATE `pokemon_speler` SET `opzak`='nee' WHERE `id`='".$pokemonid."'");

?>