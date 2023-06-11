<?php
exit;
    #Script laden zodat je nooit pagina buiten de index om kan laden 
    include("includes/security.php"); 
     
    #Je moet rank 3 zijn om deze pagina te kunnen zien 
    if($gebruiker['rank'] <= 2) header("Location: index.php?page=home"); 
     
    $page = 'steal'; 
    #Goeie taal erbij laden voor de page 
    include_once('language/language-pages.php'); 
     
//Als je drukt op stelen gebeurt het onderstaande: 
if(isset($_POST['steel'])) 
{ 
    $slachtoffer = mysql_fetch_assoc(mysql_query("SELECT user_id, username, `admin`, land, `silver`, `rank`, wereld FROM `gebruikers` WHERE `username`='".$_POST['player']."'")); 
    $rankplus = $gebruiker['rank']+1; 
    $rankmin = $gebruiker['rank']-1; 
    
    if($gebruiker['stelen'] == 0) 
        $error = '<div class="red">'.$txt['alert_no_more_steal'].'</div>'; 
	elseif($gebruiker['username'] == $_POST['player']) 
     $error = '<div class="red">Je kunt niet van je zelf stelen!</div>'; 
    elseif(empty($_POST['player'])) 
        $error = '<div class="red">'.$txt['alert_no_username'].'</div>'; 
    elseif($gebruiker['naam'] == $_POST['player']) 
        $error = '<div class="red">'.$txt['alert_steal_from_yourself'].'</div>'; 
    elseif($slachtoffer['username'] == '') 
        $error = '<div class="red">'.$txt['alert_username_dont_exist'].'</div>'; 
    elseif(!preg_match('/^([a-zA-Z0-9]+)$/is', $_POST['player'])) 
          $error = '<div class="red">'.$txt['alert_username_incorrect_signs'].'</div>'; 
    elseif($slachtoffer['admin'] == 1) 
        $error = '<div class="red">'.$txt['alert_admin_steal'].'</div>'; 
    elseif($slachtoffer['wereld'] != $gebruiker['wereld']) 
        $error = '<div class="red">'.$txt['alert_is_not_in'].' '.$gebruiker['wereld'].'.</div>'; 
    elseif($slachtoffer['rank'] < 3) 
        $error = '<div class="red">'.$txt['alert_too_low_rank'].'</div>'; 
    elseif(($slachtoffer['rank'] != $rankplus) && ($slachtoffer['rank'] != $gebruiker['rank']) && ($slachtoffer['rank'] != $rankmin)) 
        $error = '<div class="red">'.$txt['alert_too_low_or_high_rank'].'</div>'; 
    else{     
        $attackpower    = 0; 
        mysql_data_seek($pokemon_sql, 0); 
        while($attack = mysql_fetch_assoc($pokemon_sql)) 
            $attackpower += $attack['attack']; 
             
        $defencepower = 0; 
        $pokemonander    =    mysql_query("SELECT defence FROM pokemon_speler WHERE opzak = 'ja' AND user_id = '".$slachtoffer['user_id']."'"); 
        while($defence = mysql_fetch_assoc($pokemonander)) 
            $defencepower += $defence['defence']; 
             
           
        $ultimateattack = (($attackpower / 100) * (rand(0,10) + 100)); 
        $ultimatedefence = (($defencepower / 100) * (rand(0,10) + 100)); 
             
    #Stelen verlagen met 1     
    mysql_query("UPDATE `gebruikers` SET `stelen`=`stelen`-'1' WHERE `user_id`='".$_SESSION['id']."'"); 
     
    #Event taal ophalen van gebruiker 
    $eventlanguage = GetEventLanguage($slachtoffer['land']); 
    include('language/events/language-events-'.$eventlanguage.'.php'); 
     
        #Stelen is mislukt 
        if($ultimateattack <= $ultimatedefence){ 
            $straf = rand(1,2); 
            if($straf == 1){ 
                $error = '<div class="red">'.$txt['alert_steal_failed_1'].' '.$_POST['player'].' '.$txt['alert_steal_failed_2'].'</div>'; 
                $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> '.$gebruiker['username'].' '.$txt['event_steal_failed']; 
            } 
            else{ 
                $error = '<div class="red">'.$txt['alert_steal_jail'].'</div>'; 
                $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> '.$gebruiker['username'].' '.$txt['event_steal_jail']; 
            } 
        } 
        #Stelen is gelukt 
        elseif($ultimateattack > $ultimatedefence){                 
            $hoeveelstelen = round(($slachtoffer['silver'] / 100) * rand(10,30)); 
            #silver eraf halen bij diegene die is bestolen 
            mysql_query("UPDATE gebruikers SET silver = silver - '".$hoeveelstelen."' WHERE username = '".$_POST['player']."'"); 
            #silver erbij voor aanvaller 
            mysql_query("UPDATE gebruikers SET silver = silver + '".$hoeveelstelen."' WHERE user_id = '".$_SESSION['id']."'"); 
                 
            $error = '<div class="green">'.$txt['success_stole_1'].' <img src="images/icons/silver.png" alt="Silver"> '.$hoeveelstelen.' '.$txt['success_stole_2'].'.</div>'; 
            $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> '.$gebruiker['username'].' '.$txt['event_success_stole_1'].' <img src="images/icons/silver.png" alt="Silver" width="16" height="16" class="imglower" /> '.highamount($hoeveelstelen).' '.$txt['event_success_stole_2']; 
        }     
         
      if(preg_match("/opgepakt/i", $error)) { 
          $tijd = rand(120,480); 
        $time = date("i:s", $tijd); 
          $time = explode(":", $time); 
          $error = substr($error, 0, -6);   
        $error .= $txt['alert_steal_jail_text_1']." ".$time[0]." ".$txt['alert_steal_jail_text_2']." ".$time[1]." ".$txt['alert_steal_jail_text_3']."</div>"; 
      $tijdnu = date('Y-m-d H:i:s'); 
      mysql_query("UPDATE `gebruikers` SET `gevangenistijd`='".$tijd."', `gevangenistijdbegin`='".$tijdnu."' WHERE `user_id`='".$_SESSION['id']."'"); 
    } 
     
    #Event opslaan 
    mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
    VALUES (NULL, NOW(), '".$slachtoffer['user_id']."', '".$event."', '0')"); 
     
    } 
} 

if(isset($_POST['steel'])) echo $error; 
?> 
<center><p><?php echo $txt['title_text']; ?> 
<?php  
if($gebruiker['premiumaccount'] == 0) 
  echo $txt['steal_premium_text']; 
?> 
<?php echo $txt['steal_how_much_1'].' '.$gebruiker['stelen'].' '.$txt['steal_how_much_2']; ?></p></center> 
<form method="post"> 
  <center> 
    <table width="300" cellpadding="0" cellspacing="0"> 
      <td width="150"><strong><?php echo $txt['username']; ?></strong></td> 
      <td width="150"><input type="text" name="player" class="text_long" value="<?php if(isset($_POST['steel'])) echo $_POST['player']; else echo $_GET['player']; ?>" /></td> 
      </tr> 
      <tr> 
      <td></td> 
      <td><div style="padding-top:2px;"><input type="submit" name="steel" class="button" value="<?php echo $txt['button']; ?>" /></div></td> 
    </table> 
  </center> 
</form>