<?php
include_once ('cronConfig.php');

$time_now = strtotime(date("H:i:s"));
$tour_sql = mysql_query("SELECT toernooi, tijd FROM toernooi WHERE deelnemers='0' AND sluit='".date("Y-m-d")."' ORDER BY toernooi DESC");
if(mysql_num_rows($tour_sql) > 0){
  $tour = mysql_fetch_assoc($tour_sql);
  $dif = strtotime($tour['tijd'])-$time_now;
  if(($dif < 7200) AND ($dif > 0)){
    $spelers = mysql_num_rows(mysql_query("SELECT user_id FROM toernooi_inschrijving WHERE toernooi='".$tour['toernooi']."' ORDER BY id ASC"));
    $rondes = 0;
    do{
      $rondes++;
      $places = pow(2, $rondes);
    }
    while($places-$spelers <= 0);
    $rondes--;
    $rounds = $rondes;

    $inserts = pow(2, $rondes);

    $tickets_sql = mysql_query("SELECT user_id FROM toernooi_inschrijving WHERE toernooi='".$tour['toernooi']."' ORDER BY id ASC LIMIT 0, ".$inserts."");
    $inserts /= 2;
    $ins_arr = Array();
    $ver_arr = Array();
    $new_ver_arr = Array();
    while($tickets = mysql_fetch_assoc($tickets_sql)){
      if($inserts > 0){
        $inserts--;
        mysql_query("INSERT INTO toernooi_ronde (toernooi, ronde, user_id_1, gereed)
          VALUES ('".$tour['toernooi']."', '".$rondes."', '".$tickets['user_id']."', '2')");
        array_push($ins_arr, mysql_insert_id());
        array_push($ver_arr, mysql_insert_id());
      }
      else{
        shuffle($ins_arr);
        mysql_query("UPDATE toernooi_ronde SET user_id_2='".$tickets['user_id']."' WHERE id='".array_pop($ins_arr)."'");
      }
    }

    #Vervolg rondes invoeren
    while($rondes > 1){
      $rondes--;
      $inserts = (pow(2, $rondes))/2;
      while($inserts > 0){
        shuffle($ver_arr);
        mysql_query("INSERT INTO toernooi_ronde (toernooi, ronde, user_id_1, user_id_2)
          VALUES ('".$tour['toernooi']."', '".$rondes."', '-".array_pop($ver_arr)."', '-".array_pop($ver_arr)."')");
        array_push($new_ver_arr, mysql_insert_id());
        $inserts--;
      }
      $ver_arr = $new_ver_arr;
      $new_ver_arr = Array();
    }

    mysql_query("UPDATE toernooi SET ronde='".$rounds."', huidige_ronde='".$rounds."', deelnemers='".pow(2, $rounds)."' WHERE toernooi='".$tour['toernooi']."'");
    mysql_query("DELETE FROM toernooi_inschrijving WHERE toernooi='".$tour['toernooi']."'");
  }
}
else{
  $tour = mysql_fetch_assoc(mysql_query("SELECT toernooi, huidige_ronde, tijd FROM toernooi WHERE deelnemers!='0' AND no_1='0' AND last_check!='".date("Y-m-d")."' ORDER BY toernooi DESC"));
  if($time_now-strtotime($tour['tijd']) > 1800){
    $ronde_sql = mysql_query("SELECT id, ronde, user_id_1, user_id_2 FROM toernooi_ronde WHERE toernooi='".$tour['toernooi']."' AND ronde='".$tour['huidige_ronde']."' AND winnaar_id='0'");
    while($ronde = mysql_fetch_assoc($ronde_sql)){
      if(mysql_num_rows(mysql_query("SELECT id FROM duel WHERE ronde_id='".$ronde['id']."'")) == 0){
        $winnaar = 1;
        if(rand(1,100)%2){
          $winnaar = 2;
        }
        if($ronde['ronde'] != 1){
          mysql_query("UPDATE toernooi_ronde SET user_id_1='".$ronde['user_id_'.$winnaar]."', gereed=gereed+'1' WHERE user_id_1='-".$ronde['id']."' AND `gereed`<'3'");
          mysql_query("UPDATE toernooi_ronde SET user_id_2='".$ronde['user_id_'.$winnaar]."', gereed=gereed+'1' WHERE user_id_2='-".$ronde['id']."' AND `gereed`<'3'");
        }
        mysql_query("UPDATE toernooi_ronde SET winnaar_id='".$ronde['user_id_'.$winnaar]."' WHERE id='".$ronde['id']."'");
      }
    }
    if($tour['huidige_ronde'] != 1){
      $round = $tour['huidige_ronde']-1;
      #All games have been fight
      $ronde = mysql_fetch_assoc(mysql_query("SELECT gereed FROM toernooi_ronde WHERE toernooi='".$tour['toernooi']."' AND ronde='".$round."' ORDER BY gereed ASC LIMIT 1"));
      if($ronde['gereed'] == 2){
        mysql_query("UPDATE toernooi SET `huidige_ronde`=`huidige_ronde`-'1', last_check='".date("Y-m-d")."' WHERE toernooi='".$tour['toernooi']."'");
      }
    }
    else{
      $ronde = mysql_fetch_assoc(mysql_query("SELECT winnaar_id FROM toernooi_ronde WHERE toernooi='".$tour['toernooi']."' AND ronde='".$tour['huidige_ronde']."' AND winnaar_id!='0'"));
      mysql_query("UPDATE toernooi SET no_1='".$ronde['winnaar_id']."' WHERE toernooi='".$tour['toernooi']."'");
    }
  }
}

#Tijd opslaan van wanneer deze file is uitevoerd
$tijd = date("Y-m-d H:i:s");
mysql_query("UPDATE `cron` SET `tijd`='".$tijd."' WHERE `soort`='tour'");
?>