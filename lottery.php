<?php
	#Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'lottery';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>
<center>
<?

if(isset($_POST['koop'])){
  $over = mysql_num_rows(mysql_query("SELECT `id` FROM `loterij_kaarten` WHERE `uid`='".$_SESSION['id']."' AND `loterij_id`='".$_POST['id']."'"));
  $over = 10-$over;
  $lotery_info = mysql_fetch_assoc(mysql_query("SELECT `naam`, `premium`, `silver_prijs` FROM `loterij` WHERE `id`='".$_POST['id']."'"));
  $kosten = $lotery_info['silver_prijs']*$_POST['aantal'];
  
  if(($gebruiker['premiumaccount'] == 0) AND ($lotery_info['premium'] == 1))
    $error[$_POST['id']] = $txt['alert_premium_only'];
  elseif(empty($_POST['aantal']))
    $error[$_POST['id']] = $txt['alert_no_amount'];
  elseif($_POST['aantal'] < 0)
    $error[$_POST['id']] = $txt['alert_no_amount'];
  elseif(!ctype_digit($_POST['aantal']))
  	$error[$_POST['id']] = $txt['alert_unknown_amount'].'';
  elseif($_POST['aantal'] > 11)
    $error[$_POST['id']] = $txt['alert_max_10_tickets'];
  elseif($kosten > $gebruiker['silver'])
    $error[$_POST['id']] = $txt['alert_not_enough_money'];
  elseif($over == 0)
    $error[$_POST['id']] = $txt['alert_no_tickets_left'];
  elseif($over < $_POST['aantal'])
    $error[$_POST['id']] = $txt['alert_buys_left_1'].' '.$over.' '.$txt['alert_buys_left_2'];
  else{
    $good[$_POST['id']] = $txt['success_lottery'];
    for($i=0; $i<$_POST['aantal']; $i++){
      mysql_query("INSERT INTO `loterij_kaarten` (`loterij_id`, `uid`)
      VALUES ('".$_POST['id']."', '".$_SESSION['id']."')");
    }
    mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$kosten."' WHERE `user_id`='".$_SESSION['id']."'");
  }
}

echo $txt['title_text'];

#Load Lottery's
$lottery = mysql_query("SELECT `id`, `naam`, `premium`, `eind_tijd`, `silver_prijs`, `laatste_winnaar` FROM `loterij`");
for($i=0; $loterij = mysql_fetch_assoc($lottery); $i++){
  $laatste_winnaar = "-";
  if(!empty($loterij['laatste_winnaar']))
    $laatste_winnaar = '<a href="index.php?page=profile&player='.$loterij['laatste_winnaar'].'">'.$loterij['laatste_winnaar'].'</a>';

  $kaarten = mysql_fetch_assoc(mysql_query("SELECT COUNT(DISTINCT id) AS aantal_kaarten FROM  `loterij_kaarten` WHERE `loterij_id`='".$loterij['id']."'"));
  $speler = mysql_fetch_assoc(mysql_query("SELECT COUNT(DISTINCT id) AS aantal_kaarten FROM  `loterij_kaarten` WHERE `loterij_id`='".$loterij['id']."' AND `uid`='".$_SESSION['id']."'"));
  $prijzensilver = $kaarten['aantal_kaarten']*$loterij['silver_prijs'];
  $prijzensilver = number_format(round($prijzensilver),0,",",".");
  $prijskaartje = number_format(round($loterij['silver_prijs']),0,",",".");

  echo' <hr>';
  if(isset($error[$loterij['id']])){ echo '<div class="red">'.$error[$loterij['id']].'</div>'; }
  if(isset($good[$loterij['id']])){ echo '<div class="green">'.$good[$loterij['id']].'</div>'; }
  
  echo '<table width="300" border="0">';
  if($loterij['premium'] == 0){
    echo '    
      <form method="post">
        <tr>
          <td colspan="2"><center><h3>'.$loterij['naam'].' '.$txt['lottery'].'</h3></center></td>
        </tr>
        <tr> 
          <td width="170">'.$txt['time'].'</td>
          <td width="130">'.$loterij['eind_tijd'].'</td>
        </tr>
        <tr> 
          <td>'.$txt['ticket_price'].'</td>
          <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$prijskaartje.'</td>
        </tr>
        <tr> 
          <td>'.$txt['price_money'].'</td>
          <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$prijzensilver.'</td>
        </tr>
        <tr> 
          <td>'.$txt['tickets_sold'].'</td>
          <td>'.$kaarten['aantal_kaarten'].'</td>
        </tr>
        <tr> 
          <td>'.$txt['last_winner'].'</td>
          <td>'.$laatste_winnaar.'</td>
        </tr>
        <tr> 
          <td>'.$txt['buy_tickets'].' <strong>('.$speler['aantal_kaarten'].'/10)</strong></td>
          <td><input type="text" size="2" maxlength="2" class="text_short" name="aantal" /> 
            <input type="hidden" name="id" value="'.$loterij['id'].'" />
            <input type="submit" name="koop" value="'.$txt['button'].'" class="button_mini" /></td>
        </tr>
      </form>
    </table>';
  }
  else{	
    echo '    
      <form method="post">
        <tr>
          <td colspan="2"><div style="padding-bottom:10px;"><center><span class="premiumlottery">'.$loterij['naam'].' '.$txt['lottery'].'</span><br />
  		    <span class="smalltext">'.$txt['only_premium'].'</span></center></div></td>
        </tr>
        <tr> 
          <td width="170">'.$txt['time'].'</td>
          <td width="130">'.$loterij['eind_tijd'].'</td>
        </tr>
        <tr> 
          <td>'.$txt['ticket_price'].'</td>
          <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$prijskaartje.'</td>
        </tr>
        <tr> 
          <td>'.$txt['price_money'].'</td>
          <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$prijzensilver.' + <img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;"> 10</td>
        </tr>
        <tr> 
          <td>'.$txt['tickets_sold'].'</td>
          <td>'.$kaarten['aantal_kaarten'].'</td>
        </tr>
        <tr> 
          <td>'.$txt['last_winner'].'</td>
          <td>'.$laatste_winnaar.'</td>
        </tr>
        <tr> 
          <td>'.$txt['buy_tickets'].' <strong>('.$speler['aantal_kaarten'].'/10)</strong></td>
          <td><input type="text" size="2" maxlength="2" class="text_short" name="aantal" /> 
            <input type="hidden" name="id" value="'.$loterij['id'].'" />
            <input type="submit" name="koop" value="'.$txt['button'].'" class="button_mini" /></td>
        </tr>
      </form>
    </table>';
  }
}
?>
</center>