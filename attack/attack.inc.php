<?
#Load Computer Data
function computer_data($computer_id){
  global $db;

  #Load And Return All Computer Information
  $computerInformationSQL = $db->prepare("SELECT pokemon_wild.*, pokemon_wild_gevecht.* FROM pokemon_wild INNER JOIN pokemon_wild_gevecht ON pokemon_wild_gevecht.wildid = pokemon_wild.wild_id WHERE pokemon_wild_gevecht.id=:computerId");
  $computerInformationSQL->bindValue(':computerId', $computer_id, PDO::PARAM_INT);
  $computerInformationSQL->execute();

  return $computerInformationSQL->fetch(PDO::FETCH_ASSOC);
}

#Load  Pokemon Data
function pokemon_data($pokemon_id){
  global $db;

  #Load And Return All Pokemon Information
  $allInformationSQL = $db->prepare("SELECT pw.*, ps.*, psg.* FROM pokemon_wild AS pw INNER JOIN pokemon_speler AS ps ON ps.wild_id = pw.wild_id INNER JOIN pokemon_speler_gevecht AS psg ON ps.id = psg.id  WHERE psg.id=:pokemonId");
  $allInformationSQL->bindValue(':pokemonId', $pokemon_id, PDO::PARAM_INT);
  $allInformationSQL->execute();

  return $allInformationSQL->fetch(PDO::FETCH_ASSOC);
}
  
#Load Aanval logs
function aanval_log($aanval_log_id){
  global $db;

  #Load And Send Data
  $attackLogSQL = $db->prepare("SELECT * FROM `aanval_log` WHERE `id`=:attackLogId");
  $attackLogSQL->bindValue(':attackLogId', $aanval_log_id, PDO::PARAM_INT);
  $attackLogSQL->execute();

  return $attackLogSQL->fetch(PDO::FETCH_ASSOC);
}

#Knocked One Pokemon down
function one_pokemon_exp($aanval_log,$pokemon_info,$computer_info,$txt){
  global $db;
  $ids = explode(",", $aanval_log['gebruikt_id']);
  $ret['bericht'] = "<br />";
  $aantal = 0;
  #Count all pokemon
  foreach($ids as $pokemonid){
    if(!empty($pokemonid)) $aantal++;
  }
  foreach($ids as $pokemonid){
    if(!empty($pokemonid)){  
      $usedInfoSQL = $db->prepare("SELECT pokemon_wild.naam, pokemon_speler.roepnaam, pokemon_speler.trade, pokemon_speler.level, pokemon_speler.expnodig, pokemon_speler_gevecht.leven, pokemon_speler_gevecht.exp FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id INNER JOIN pokemon_speler_gevecht ON pokemon_speler.id = pokemon_speler_gevecht.id 
    WHERE pokemon_speler.id=:pokemonId");
      $usedInfoSQL->bindValue(':pokemonId', $pokemonid);
      $usedInfoSQL->execute();
      $used_info = $usedInfoSQL->fetch(PDO::FETCH_ASSOC);

      $used_info['naam_goed'] = pokemon_naam($used_info['naam'],$used_info['roepnaam']);  
      #If pokemon is dead no exp.
      if($used_info['leven'] > 0){
        #If pokemon is level 100 no more exp for him
        if($used_info['level'] < 100){
          #Check if the user is premium
          $userSQL = $db->prepare("SELECT premiumaccount FROM gebruikers WHERE user_id=:uid");
          $userSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
          $userSQL->execute();

          $user = $userSQL->fetch(PDO::FETCH_ASSOC);

          $extra_exp = $used_info['trade'];
          if($user['premiumaccount'] >= 1) $extra_exp += 0.5;
          
          #Calculate EXP, division by aantal for amount of pokemon
          $ret['exp'] = round(((($computer_info['base_exp']*$computer_info['level'])*$extra_exp*1)/7)/$aantal);
         
          #Add the exp and Effort points 

          $addStats = $db->prepare("UPDATE `pokemon_speler_gevecht` SET `exp`=`exp`+:exp, `totalexp`=`totalexp`+:totalExp, `attack_ev`=`attack_ev`+:effortAttack, `defence_ev`=`defence_ev`+:effortDefence, `speed_ev`=`speed_ev`+:effortSpeed, `spc.attack_ev`=`spc.attack_ev`+:effortSpecAttack, `spc.defence_ev`=`spc.defence_ev`+:effortSpecDefence, `hp_ev`=`hp_ev`+:effortHp WHERE `id`=:pokeId");
          $addStats->bindValue(':exp', $ret['exp']);
          $addStats->bindValue(':totalExp', $ret['exp']);
          $addStats->bindValue(':effortAttack', $computer_info['effort_attack']);
          $addStats->bindValue(':effortDefence', $computer_info['effort_defence']);
          $addStats->bindValue(':effortSpeed', $computer_info['effort_speed']);
          $addStats->bindValue(':effortSpecAttack', $computer_info['effort_spc.attack']);
          $addStats->bindValue(':effortSpecDefence', $computer_info['effort_spc.defence']);
          $addStats->bindValue(':effortHp', $computer_info['effort_hp']);
          $addStats->bindValue(':pokeId', $pokemonid);
          $addStats->execute();

          
          #Check if the Pokemon is traded
          if(($user['premiumaccount'] >= 1) && ($used_info['trade'] == "1.5")) $ret['bericht'] .= $used_info['naam_goed']." ".$txt['recieve_boost_and_premium']." ".$ret['exp']." ".$txt['exp_points']."<br />";
          elseif($user['premiumaccount'] >= 1) $ret['bericht'] .= $used_info['naam_goed']." ".$txt['recieve_premium_boost']." ".$ret['exp']." ".$txt['exp_points']."<br />";
          elseif($used_info['trade'] == "1.5") $ret['bericht'] .= $used_info['naam_goed']." ".$txt['recieve_boost']." ".$ret['exp']." ".$txt['exp_points']."<br />";
          else $ret['bericht'] .= $used_info['naam_goed']." ".$txt['recieve']." ".$ret['exp']." ".$txt['exp_points']."<br />";

        }
      }
      else $aantal -= 1;
    }
  }
  #Empty Pokemon Used For new pokemon
  $emptyUsedPokemon = $db->prepare("UPDATE `aanval_log` SET `gebruikt_id`=',".$pokemon_info['id'].",' WHERE `id`=:attackLogId");
  $emptyUsedPokemon->bindValue(':attackLogId', $aanval_log['id'], PDO::PARAM_INT);
  $emptyUsedPokemon->execute();
 
  return $ret;
}

#Let Pokemon Grow
function pokemon_grow($txt){
  global $db;
  $_SESSION['used'] = Array();
  $count = 0;
  $sql = $db->prepare("SELECT pokemon_wild.naam, pokemon_speler.id, pokemon_speler.roepnaam, pokemon_speler.level, pokemon_speler.expnodig, pokemon_speler.exp FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE user_id=:uid AND `exp`>=`expnodig` AND `opzak`='ja'");
  $sql->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
  $sql->execute();

  while($select = $sql->fetch(PDO::FETCH_ASSOC)){
    if($count == 0) $_SESSION['lvl_old'] = $select['level'];
    array_push($_SESSION['used'], $select['id']);
    $count++;
    #Change name for male and female
    $select['naam_goed'] = pokemon_naam($select['naam'],$select['roepnaam']);
    if($select['level'] < 100){
      if($select['exp'] >= $select['expnodig']){
        do{                
          $realSQL = $db->prepare("SELECT pokemon_wild.*, pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id  WHERE pokemon_speler.id=:pokemonId");
          $realSQL->bindValue(':pokemonId', $select['id'], PDO::PARAM_INT);
          $realSQL->execute();
          $real = $realSQL->fetch(PDO::FETCH_ASSOC);
                    
          #level info
          $levelnieuw = $real['level']+1;
          if($levelnieuw > 100) break;
          
          #Call Script for Calulcalate New stats
          $expnodig = nieuwestats($real,$levelnieuw,$real['exp']);
      
          #Check if Pokemon is growing a level
          if((!$_SESSION['aanvalnieuw']) AND (!$_SESSION['evolueren'])) $toestemming = levelgroei($levelnieuw,$real);
    
          #make Log
          $pokemonnaam = htmlspecialchars($select['naam_goed'], ENT_QUOTES);
		  
			#Event taal pack includen
			//$eventlanguagesql = mysql_fetch_assoc(mysql_query("SELECT land FROM gebruikers WHERE user_id = '".$_SESSION['id']."'"));
			//$eventlanguage = GetEventLanguage($eventlanguagesql['land']);
            //include('../slanguage/events/language-events-'.$eventlanguage.'.php');
            include('../language/events/language-events-nl.php');
            $txt['event_is_level_up'] = 'heeft een nieuw level!';

            if($pokemonnaam) {
              $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> ' . $pokemonnaam . ' ' . $txt['event_is_level_up'];
              #Melding geven aan de uitdager

              $giveMessage = $db->prepare("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
        VALUES (NULL, NOW(), :uid, :event, '0')");
              $giveMessage->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
              $giveMessage->bindValue(':event', $event);
              $giveMessage->execute();
            }

        }while($expnodig < $real['exp']-$real['expnodig']);
      }
    }
  }
}

#Update Pokemon PLayer Hand
function pokemon_player_hand_update(){
  global $db;
  #Copy Life en Effect Stats to pokemon_speler table

  $player_hand_query = $db->prepare("SELECT `id`, `leven`, `exp`, `totalexp`, `effect`, `attack_ev`, `defence_ev`, `speed_ev`, `spc.attack_ev`, `spc.defence_ev`, `hp_ev` FROM `pokemon_speler_gevecht` WHERE `user_id`=:uid");
  $player_hand_query->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
  $player_hand_query->execute();

  while($player_hand = $player_hand_query->fetch(PDO::FETCH_ASSOC)){
    $updatePokemonPlayer = $db->prepare("UPDATE `pokemon_speler` SET `leven`=:life, `exp`=:exp, `totalexp`=:totalExp, `effect`=:effect, `attack_ev`=`attack_ev`+:attackEv, `defence_ev`=`defence_ev`+:defenceEv, `speed_ev`=`speed_ev`+:speedEv, `spc.attack_ev`=`spc.attack_ev`+:specialAttackEv, `spc.defence_ev`=`spc.defence_ev`+:specialDefenceEv, `hp_ev`=`hp_ev`+:hpEv WHERE `id`=:id");
    $updatePokemonPlayer->bindValue(':life', $player_hand['leven']);
    $updatePokemonPlayer->bindValue(':exp', $player_hand['exp']);
    $updatePokemonPlayer->bindValue(':totalExp', $player_hand['totalexp']);
    $updatePokemonPlayer->bindValue(':effect', $player_hand['effect']);
    $updatePokemonPlayer->bindValue(':attackEv', $player_hand['attack_ev']);
    $updatePokemonPlayer->bindValue(':defenceEv', $player_hand['defence_ev']);
    $updatePokemonPlayer->bindValue(':speedEv', $player_hand['speed_ev']);
    $updatePokemonPlayer->bindValue(':specialAttackEv', $player_hand['spc.attack_ev']);
    $updatePokemonPlayer->bindValue(':specialDefenceEv', $player_hand['spc.defence_ev']);
    $updatePokemonPlayer->bindValue(':hpEv', $player_hand['hp_ev']);
    $updatePokemonPlayer->bindValue(':id', $player_hand['id'], PDO::PARAM_INT);
    $updatePokemonPlayer->execute();
  }
}

#Remove All Attack Data
function remove_attack($aanval_log_id){
  global $db;
  #Remove Attack
  $removeAttackPage = $db->prepare("UPDATE `gebruikers` SET `pagina`='attack_start' WHERE `user_id`=:uid");
  $removeAttackPage->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
  $removeAttackPage->execute();

  $deleteWildFight = $db->prepare("DELETE FROM `pokemon_wild_gevecht` WHERE `aanval_log_id`=:attackLogId");
  $deleteWildFight->bindValue(':attackLogId', $aanval_log_id, PDO::PARAM_INT);
  $deleteWildFight->execute();

  $deletePlayerFight = $db->prepare("DELETE FROM `pokemon_speler_gevecht` WHERE `aanval_log_id`=:attackLogId");
  $deletePlayerFight->bindValue(':attackLogId', $aanval_log_id, PDO::PARAM_INT);
  $deletePlayerFight->execute();

  $deleteAttackLog = $db->prepare("DELETE FROM `aanval_log` WHERE `id`=:attackLogId");
  $deleteAttackLog->bindValue(':attackLogId', $aanval_log_id, PDO::PARAM_INT);
  $deleteAttackLog->execute();
}

#Advantage (Water Against Fire)
function attack_to_defender_advantage($soort,$defender){
  global $db;
  #Gegevens laden uit de database
  $voordeelSQL = $db->prepare("SELECT `krachtiger` FROM `voordeel` WHERE `aanval`=:soort AND (`verdediger`=:type1 OR `verdediger`=:type2)");
  $voordeelSQL->bindValue(':soort', $soort);
  $voordeelSQL->bindValue(':type1', $defender['type1']);
  $voordeelSQL->bindValue(':type2', $defender['type2']);
  $voordeelSQL->execute();

  $voordeel = $voordeelSQL->fetch(PDO::FETCH_ASSOC);

  #Als er geen voordeel is, deze gegeven als 1 omdat anders de formule niet werkt
  if($voordeel['krachtiger'] == "") return 1; 
  #als het 0.00 is heeft aanval geen effect op de tegenstander
  elseif($voordeel['krachtiger'] == "0.00") return 0;
  #Anders het voordeel gebruiken
  else return $voordeel['krachtiger'];
}

#Attacker advantege 
function attacker_with_attack_advantage($attacker_info,$attack_info){
  global $db;
  if($attacker_info['type1'] OR $attacker_info['type2'] == $attack_info['soort']) return 1.5;
  else return 1;
}

#Multiple Hits
function multiple_hits($attack,$damage){
  global $db;
  #2-5 times?
  if($attack['aantalkeer'] == "2-5"){
    $kans = rand(1,4);
    #is kans niet 2, dan word aanval 2-3 keer uitgevoerd
    if($kans != 2){
      #Kijken hoeveek het echt word
      $times = rand(2,3);
      #Nieuwe levenaf berekenen
      $multi_hit['damage'] = $damage*$times;
      $multi_hit['message'] = "<br />".$attack['naam']." hits ".$times." times. ";
    }
    #Is het wel 2 dan word het 4-5 keer uitgevoerd
    else{
      #Kijken hoeveek het echt word
      $times = rand(4,5);
      #Nieuwe levenaf berekenen
      $multi_hit['damage'] = $damage*$times;
      $multi_hit['message'] = "<br />".$attack['naam']." hits ".$times." times. ";
    }
  }
  elseif($attack['aantalkeer'] == "1-3"){
    $times = rand(1,3);
    #Nieuwe levenaf berekenen
    $multi_hit['damage'] = $damage*$times;
    $multi_hit['message'] = "<br />".$attack['naam']." hits ".$times." times. ";
  }
  elseif($attack['aantalkeer'] == "gezond_opzak"){
    #Attack as many times as player have helaty pokemon in hand
    $alifePokemon = $db->prepare("SELECT `id` FROM `pokemon_speler_gevecht` WHERE `user_id`=:uid AND `effect`='' AND `leven`>'0'");
    $alifePokemon->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
    $alifePokemon->execute();
    $times = $alifePokemon->rowCount();
    #Nieuwe levenaf berekenen
    $multi_hit['damage'] = $damage*$times;
    $multi_hit['message'] = "<br />".$attack['naam']." hits ".$times." times. ";
  }
  else{
    #Nieuwe levenaf berekenen
    $multi_hit['damage'] = $damage*$attack['aantalkeer'];
    $multi_hit['message'] = "<br />".$attack['naam']." hits ".$attack['aantalkeer']." times. ";
  }
  return $multi_hit;
}

#Calculate the amount of life thats going away
function life_formula($attacker_info,$opponent_info,$attack_info){
  #Check if the attack has a strength
  if($attack_info['sterkte'] != 0){
    #Check if attack is in advantage against oponent. Example: Water Against Fire
    $attack_adv = attack_to_defender_advantage($attack_info['soort'],$opponent_info)*10;
    #Check if attack is in advantage with attacker. Example Electric Pokemon does Thunder
    $attacker_adv = attacker_with_attack_advantage($attacker_info,$attack_info);
    #Generate Luck
    $luck = rand(217,255);
    if($opponent_info['defence'] <= 0) $opponent_info['defence'] = 1;
    
    #((2A/5+2)*B*C)/D)/50)+2)*X)*Y/10)*Z)/255
    # A = level
    # B = aanvallers attack
    # C = Kracht van de aanval
    # D = Verdedigers defence
    # X = Aanval type zelfde als Pokemon type. Zo ja dan 1.5, anders 1
    # Y = voordeel van de aanval
    # Z = willekeurig getal tussen 217 en 255
  
    $life_of = round(((((((((2*$attacker_info['level']/5+2)*$attacker_info['attack']*$attack_info['sterkte'])/$opponent_info['defence'])/50)+2)*$attacker_adv)*$attack_adv/10)*$luck)/255);
    return $life_of;
  }
  else return 0;
}
?>
