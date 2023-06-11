<?
if(isset($_GET['duel_id'])){
  //Session On
  session_start();  
  //Connect With Database
  include_once("../../includes/config.php");
  //include duel functions
  include_once("duel.inc.php");
  //Load Duel Data
  $duel_sql = mysql_query("SElECT `id`, `datum`, `uitdager`, `tegenstander`, `t_pokemonid`, `status` FROM `duel` WHERE `id`='".$_GET['duel_id']."'");
  if(mysql_num_rows($duel_sql) == 1){
    $duel = mysql_fetch_array($duel_sql);
    $time = strtotime(date("Y-m-d H:i:s"))-strtotime($duel['datum']);
    if($duel['status'] == "accept"){
      $status = 3;
      $_SESSION['duel']['duel_id'] = $_GET['duel_id'];
      $_SESSION['duel']['begin_zien'] = true;
      start_attack($duel);
    }
  	elseif($duel['status'] == "no_money"){
  	  $status = 4;
  	  mysql_query("DELETE FROM `duel` WHERE `id`='".$_GET['duel_id']."'");
      //Remove Duel
      mysql_query("UPDATE `gebruikers` SET `pagina`='duel_start' WHERE `user_id`='".$_SESSION['id']."'");
      mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `duel_id`='".$_GET['duel_id']."'");
  	}
  	elseif($duel['status'] == "all_dead"){
  	  $status = 5;
  	  mysql_query("DELETE FROM `duel` WHERE `id`='".$_GET['duel_id']."'");
      //Remove Duel
      mysql_query("UPDATE `gebruikers` SET `pagina`='duel_start' WHERE `user_id`='".$_SESSION['id']."'");
      mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `duel_id`='".$_GET['duel_id']."'");
  	}
    elseif($time > 60){
      mysql_query("DELETE FROM `duel` WHERE `id`='".$_GET['duel_id']."'");
      //Remove Duel
      mysql_query("UPDATE `gebruikers` SET `pagina`='duel_start' WHERE `user_id`='".$_SESSION['id']."'");
      mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `duel_id`='".$_GET['duel_id']."'");
      $status = 1;
    }
    else $status = 0;
  }
  else $status = 2;
  echo $status;
}
?>