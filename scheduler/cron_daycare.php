<?php
include_once ('cronConfig.php');

  $daycare_sql = mysql_query("SELECT pokemonid, level, levelup
                                                  FROM daycare WHERE ei = '0'
                                                  AND levelup < '15'
                                                  ORDER BY id");
  while ($daycare = mysql_fetch_assoc($daycare_sql)) {
    $leveltotal = $daycare['level'] + $daycare['levelup'];
    if ($leveltotal < 100) {
      $levelup = rand(0, 1);
      #Pokemon lvlup updaten naar +0 of +1
      mysql_query("UPDATE daycare SET levelup = levelup + '" . $levelup . "' WHERE pokemonid = '" . $daycare['pokemonid'] . "'");
    }
  }

#-------------------------------- EI CHECK ------------------------------------#

  $sql = mysql_query("SELECT user_id, COUNT( user_id ) AS owner FROM daycare GROUP BY user_id");

  while ($daycare = mysql_fetch_assoc($sql)) {
    $random = rand(1, 4);
    if ($random == 1) {
      if ($daycare['owner'] == 2) {
        $daycare_sql = mysql_query("SELECT pokemonid, user_id, naam FROM daycare WHERE `user_id`='" . $daycare['user_id'] . "' ORDER BY id");
        for ($i = 1; $daycare = mysql_fetch_assoc($daycare_sql); $i++) {
          if ($i == 1) {
            $pokemon1 = $daycare['naam'];
            $shinysql1 = mysql_fetch_assoc(mysql_query("SELECT shiny FROM pokemon_speler WHERE id = '" . $daycare['pokemonid'] . "'"));
          } elseif ($i == 2) {
            $pokemon2 = $daycare['naam'];
            $shinysql2 = mysql_fetch_assoc(mysql_query("SELECT shiny FROM pokemon_speler WHERE id = '" . $daycare['pokemonid'] . "'"));
          }
          $user_id = $daycare['user_id'];
        }

        #Kijken of beide pokemon shiny zijn
        if ($shinysql1['shiny'] == 1 && $shinysql2['shiny'] == 1) $shiny = 1;
        else $shiny = 0;
        if (($pokemon1 == $pokemon2) OR ($pokemon1 == 'Ditto') OR ($pokemon2 == 'Ditto')) {
          if ($pokemon1 == "Ditto") $pokemon = $pokemon2;
          else $pokemon = $pokemon1;
          #Check if pokemon is not rare
          $rare = mysql_fetch_assoc(mysql_query("SELECT `wild_id`, `zeldzaamheid` FROM `pokemon_wild` WHERE `naam`='" . $pokemon . "'"));
          if ($rare['zeldzaamheid'] != 3) {
            $wildid = $rare['wild_id'];
            while (1) {
              $level_sql = mysql_query("SELECT `wild_id` FROM `levelen` WHERE `nieuw_id`='" . $wildid . "' AND `wat`='evo'");
              if (mysql_num_rows($level_sql) == 0) break;
              else {
                $select = mysql_fetch_assoc($level_sql);
                if ($wildid != $select['wild_id']) $wildid = $select['wild_id'];
              }
            }
            $name = mysql_fetch_assoc(mysql_query("SELECT `naam` FROM pokemon_wild WHERE wild_id='" . $wildid . "'"));
            #Eitje in daycare zetten
            mysql_query("INSERT INTO daycare SET level = '5', levelup = '" . $shiny . "', user_id = '" . $user_id . "', naam = '" . $name['naam'] . "', ei = '1'");
          }
        }
      }
    }
  }

  #-------------------------------- / EI CHECK ------------------------------------#


  #Tijd opslaan van wanneer deze file is uitevoerd
  $tijd = date("Y-m-d H:i:s");
  mysql_query("UPDATE `cron` SET `tijd`='" . $tijd . "' WHERE `soort`='daycare'");
?>