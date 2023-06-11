<? //Is all the information send
if( (isset($_GET['aanval_log_id'])) && (isset($_GET['sid']))){
  //Session On
  session_start();
  //Connect With Database
  include_once("../../includes/config.php");
  //Include Default Functions
  include_once("../../includes/ingame.inc.php");
  //Include Attack Functions
  include("../attack.inc.php"); 
  //Goeie taal erbij laden voor de page
  include_once('../../language/language-general.php');
  //Load Data
  $aanval_log = aanval_log($_GET['aanval_log_id']);
  //Load User Information
  $gebruikerSQL = $db->prepare("SELECT * FROM `gebruikers`, `gebruikers_item` WHERE ((`gebruikers`.`user_id`=:uid) AND (`gebruikers_item`.`user_id`=:uid))");
  $gebruikerSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
  $gebruikerSQL->execute();

  $gebruiker = $gebruikerSQL->fetch(PDO::FETCH_ASSOC);

  //Load computer info
  $computer_info = computer_data($aanval_log['tegenstanderid']);
  //Test if fight is over
  if($aanval_log['laatste_aanval'] == "end_screen"){
		if($computer_info['leven'] == 0){
      rankerbij('attack',$txt);  
      //Update User
      $updateUser = $db->prepare("UPDATE `gebruikers` SET `gewonnen`=`gewonnen`+'1' WHERE `user_id`=:uid");
      $updateUser->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
      $updateUser->execute();
      $text = 1;
      $money = 0;
    }
    else{
      if($gebruiker['rank'] >= 4) rankeraf('attack_lose'); 
      //Rank Higher Than 3 Decrease silver with 25%
      if($gebruiker['rank'] >= 3) $money = round($gebruiker['silver']/4);
      else $money = 0;
      //Update user
      $updateUser = $db->prepare("UPDATE `gebruikers` SET `silver`=`silver`-:newMoney, `verloren`=`verloren`+'1' WHERE `user_id`=:uid");
      $updateUser->bindValue(':newMoney', $money, PDO::PARAM_INT);
      $updateUser->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
      $updateUser->execute();

      $text = 0;
    }
    echo $text." | ".$money;
    //Sync pokemon
    pokemon_player_hand_update();
    //Let Pokemon grow
    pokemon_grow($txt);
    //Remove Attack
    remove_attack($_GET['aanval_log_id']);
    unset($_SESSION['attack']['aanval_log_id']);
  }
  else{
    header("Location: ?page=attack/trainer/trainer-attack");
  }
}