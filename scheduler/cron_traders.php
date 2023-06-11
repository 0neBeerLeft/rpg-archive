<?php
include_once ('cronConfig.php');

  $traders_sql = mysql_query("SELECT * FROM traders");
  while($trader = mysql_fetch_assoc($traders_sql)){
    if(empty($trader['wil'])){
      #Willekeurige pokemon laden
      $query = mysql_fetch_assoc(mysql_query("SELECT naam, zeldzaamheid FROM pokemon_wild WHERE wereld != 'Isshu' AND evolutie = '1' ORDER BY rand() limit 1"));

      $wil = mysql_fetch_assoc(mysql_query("SELECT naam FROM pokemon_wild WHERE zeldzaamheid = '".$query['zeldzaamheid']."' AND naam != '".$query['naam']."' AND wereld != 'Isshu' AND evolutie = '1' ORDER BY rand() limit 1"));

      #De willekeurige pokemon in de traders database zetten
      mysql_query("UPDATE traders SET naam = '".$query['naam']."', wil = '".$wil['naam']."' WHERE eigenaar = '".$trader['eigenaar']."'");
    }
  }

  #Tijd opslaan van wanneer deze file is uitevoerd
  $tijd = date("Y-m-d H:i:s");
  mysql_query("UPDATE `cron` SET `tijd`='".$tijd."' WHERE `soort`='trader'");
?>

