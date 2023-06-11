<?
include('../includes/config.php');

$op_nu = 1;
foreach($_POST['listContainer'] as $pokemonid) {
	$pokemonid = (int) $pokemonid;
	mysql_query("UPDATE `pokemon_speler` SET `opzak_nummer`='".$op_nu."' WHERE `id`='".$pokemonid."'");
	$op_nu++;
}
?>