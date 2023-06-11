<? //Is all the information send
if((isset($_GET['duel_id'])) && (isset($_GET['sid']))){
  //Session On
  session_start();
  //Connect With Database
  include_once("../../includes/config.php");
  //Include Default Functions
  include_once("../../includes/ingame.inc.php");
  //Include Attack Functions
  include("../attack.inc.php");
  //Include Duel Functions
  include_once("duel.inc.php"); 
  //Load language
  include_once('../../language/language-general.php');
  //Load duel info
  $duel_info = duel_info($_GET['duel_id']);
    
  if($duel_info['uitdager'] == $_SESSION['naam']){
    $you = mysql_fetch_array(mysql_query("SELECT user_id, username,missie_2 FROM gebruikers WHERE username='".$_SESSION['naam']."'"));
    $other =  mysql_fetch_array(mysql_query("SELECT user_id, username FROM gebruikers WHERE username='".$duel_info['tegenstander']."'"));
    $you_ch = $duel_info['u_character'];
    $other_ch = $duel_info['t_character'];
    $other_id = $duel_info['t_used_id'];
    $dood_1 = mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler_gevecht WHERE leven='0' AND user_id='".$you['user_id']."'"));
    $dood_2 = mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler_gevecht WHERE leven='0' AND user_id='".$other['user_id']."'"));
  }
  elseif($duel_info['tegenstander'] == $_SESSION['naam']){
    $you = mysql_fetch_array(mysql_query("SELECT user_id, username,missie_2 FROM gebruikers WHERE username='".$_SESSION['naam']."'"));
    $other =  mysql_fetch_array(mysql_query("SELECT user_id, username FROM gebruikers WHERE username='".$duel_info['uitdager']."'"));
    $you_ch = $duel_info['t_character'];
    $other_ch = $duel_info['u_character'];
    $other_id = $duel_info['u_used_id'];
    $dood_1 = mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler_gevecht WHERE leven='0' AND user_id='".$other['user_id']."'"));
    $dood_2 = mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler_gevecht WHERE leven='0' AND user_id='".$you['user_id']."'"));
  }
  //Update Hand Pokemon
  pokemon_player_hand_update();
  //Grow Pokemon
  pokemon_grow($txt);
  
  if($_SESSION['naam'] == $duel_info['winner']){
    //Save log
    mysql_query("INSERT INTO duel_logs (`datum`, `win`, `lose`)
      VALUES ( '".date("Y-m-d H:i:s")."', '".$you['user_id']."', '".$other['user_id']."')");

    if($duel_info['bedrag'] > 0){
      mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$duel_info['bedrag']."' WHERE `user_id`='".$other['user_id']."'");
      mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$duel_info['bedrag']."' WHERE `user_id`='".$you['user_id']."'");
    }
    
    if($duel_info['ronde_id'] != 0){
      mysql_query("UPDATE toernooi_ronde SET dood_1='".$dood_1."', dood_2='".$dood_2."', winnaar_id='".$you['user_id']."' WHERE ronde='".$duel_info['ronde_id']."' ORDER BY toernooi DESC");
      mysql_query("UPDATE toernooi_ronde SET user_id_1='".$you['user_id']."', gereed=gereed+'1' WHERE user_id_1='-".$duel_info['ronde_id']."'");
      mysql_query("UPDATE toernooi_ronde SET user_id_2='".$you['user_id']."', gereed=gereed+'1' WHERE user_id_2='-".$duel_info['ronde_id']."'");
    }
    //complete mission 2
    if($gebruiker['missie_2'] == 0){
      mysql_query("UPDATE `gebruikers` SET `missie_2`=1, `silver`=`silver`+500,`rankexp`=rankexp+50 WHERE `user_id`='".$you['user_id']."'");
      echo showToastr("info", "Je hebt een missie behaald!");
    }
    
    rankerbij('duel',$txt);
    $text = 1;
  }
  else{
    $text = 2;
    rankeraf('attack_lose'); 
  }

  if($duel_info['status'] == 'finish') remove_duel($duel_info['id']);
  
  mysql_query("UPDATE `duel` SET `status`='finish' WHERE `id`='".$_GET['duel_id']."'"); 
                             
  unset($_SESSION['duel']['duel_id']);

  echo $text." | ".$duel_info['bedrag']." | ".$you." | ".$other." | ".$you_ch." | ".$other_ch." | ".$other_id;  
}
?>