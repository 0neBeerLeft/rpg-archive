<?
session_start();
//Verbinding met de database maken
include('../includes/config.php');

$uid = (int)$_GET["pokemonid"];

//Verwijderen uit transferlijst tabel
mysql_query("DELETE FROM `transferlijst` WHERE `pokemon_id`='".$uid."' AND `user_id`='".$_SESSION['id']."'");

//pokemon opslaan als zijne van transferlijst
mysql_query("UPDATE `pokemon_speler` SET `opzak`='nee' WHERE `id`='".$uid."' AND `user_id`='".$_SESSION['id']."'");


?>