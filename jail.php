<?php	
//include dit script als je de pagina alleen kunt zien als je ingelogd bent.	
include('includes/security.php');

$page = 'jail';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
?>

<center>  

  <table width="600" border="0">
    <tr>
    	<td><center><?php echo $txt['title_text']; ?><br /></center></td>    
    </tr>  
  </table><br />  

  <table width="600" border="0" cellpadding="0" cellspacing="0">  
    <?php        
    #JOUW KLOK dus van session naam
    $tijdgevangenisoverjou = strtotime($gebruiker['gevangenistijdbegin']);
    $tijdgevangenisoverjou = $tijdgevangenisoverjou+$gebruiker['gevangenistijd'];
    $tijdgevangenisnujou   = strtotime(date('Y-m-d H:i:s'));
    $tijdgevangenisjou     = $tijdgevangenisoverjou-$tijdgevangenisnujou;

    if(isset($_POST['bust'])){
      $jail_bust = mysql_fetch_assoc(mysql_query("SELECT username, land, gevangenistijd, gevangenistijdbegin FROM `gebruikers` WHERE `user_id`='".$_POST['id']."'"));
      $kans = rand(0,100);
      $jail_tijd_over = strtotime($jail_bust['gevangenistijdbegin'])+$jail_bust['gevangenistijd'];
      $jail_tijd_nu2  = date('Y-m-d H:i:s');
      $jail_tijd_nu   = strtotime(date('Y-m-d H:i:s'));
      $jail_tijd_check= $jail_tijd_over-$jail_tijd_nu;
      $jail_tijd      = $jail_tijd_check*2;
      $jail_tijd_mooi = date("i:s", $jail_tijd); 

      if($jail_bust['gevangenistijd'] == 0)
        echo '<div class="blue">'.$txt['alert_already_broke_out'].'</div>';
      elseif($jail_tijd_check < 0)
        echo '<div class="blue">'.$txt['alert_already_free'].'</div>';
      elseif($kans <= 20){
        echo '<div class="green">'.$txt['success_bust'].'</div>';      

        mysql_query("UPDATE `gebruikers` SET `gevangenistijd`='0' WHERE `user_id`='".$_POST['id']."'");
		
		#Event taal pack includen
		$eventlanguage = GetEventLanguage($jail_bust['land']);
		include('language/events/language-events-'.$eventlanguage.'.php');
			
        #Gebeurtenis Sturen Naar De speler die is uitgebroken
        $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> '.$gebruiker['username'].' '.$txt['event_bust'];

		#Melding geven aan de uitdager
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
		VALUES (NULL, NOW(), '".$_POST['id']."', '".$event."', '0')");
		
		#Rank erbij doen
        rankerbij('jail',$txt);
      }    

      else{    
        echo '<div class="red">'.$txt['alert_bust_failed_1'].' '.$jail_tijd_mooi.' '.$txt['alert_bust_failed_2'].'</div>';
        mysql_query("UPDATE `gebruikers` SET `gevangenistijdbegin`='".$jail_tijd_nu2."', `gevangenistijd`='".$jail_tijd."' WHERE `user_id`='".$_SESSION['id']."'");
        $tijdgevangenisjou = $jail_tijd;
      }
      unset($_POST['bust']);
    } 

    if(isset($_POST['koop'])){
      $jail_koop = mysql_fetch_assoc(mysql_query("SELECT username, land, gevangenistijd, gevangenistijdbegin FROM `gebruikers` WHERE `user_id`='".$_POST['id']."'")); 
      $jail_tijd_over = strtotime($jail_koop['gevangenistijdbegin'])+$jail_koop['gevangenistijd'];
      $jail_tijd_nu   = strtotime(date('Y-m-d H:i:s'));
      $jail_tijd      = $jail_tijd_over-$jail_tijd_nu;
      $jail_kosten    = $jail_tijd*4;

      if($jail_koop['gevangenistijd'] == 0)
        echo '<div class="blue">'.$txt['alert_already_broke_out'].'</div>';
      elseif($jail_tijd < 0)
        echo '<div class="blue">'.$txt['alert_already_free'].'</div>';
      elseif($jail_kosten < 0)
        echo '<div class="blue">'.$txt['alert_already_free'].'</div>';
      elseif($gebruiker['silver'] < $jail_kosten)
        echo '<div class="red">'.$txt['alert_not_enough_silver'].'</div>';  
      else{
        echo '<div class="green">'.$txt['success_bought'].' <img src="images/icons/silver.png" title="Silver"> '.highamount($jail_kosten).'</div>';   

        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$jail_kosten."' WHERE `user_id`='".$_SESSION['id']."'");
        mysql_query("UPDATE `gebruikers` SET `gevangenistijd`='0' WHERE `user_id`='".$_POST['id']."'");
		
		#Event taal pack includen
		$eventlanguage = GetEventLanguage($jail_koop['land']);
		include('language/events/language-events-'.$eventlanguage.'.php');
		
        $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> '.$gebruiker['username'].' '.$txt['event_bought'].' <img src="images/icons/silver.png" title="Silver"> '.highamount($jail_kosten).' silver.';
		
		#Melding geven aan de uitdager
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
		VALUES (NULL, NOW(), '".$_POST['id']."', '".$event."', '0')");
      }
      unset($_POST['koop']);
    }   

    $kijker_injail = 0;
    $injail = 0;
    $jailsql = mysql_query("SELECT `user_id`, `username`, `wereld`, `land`, `gevangenistijd`, `gevangenistijdbegin`, COUNT(DISTINCT `user_id`) AS \"aantal\" FROM `gebruikers` WHERE `gevangenistijd`!='' AND `gevangenistijd`!='0' AND `wereld`='".$gebruiker['wereld']."' GROUP BY `user_id`");   

    while($row = mysql_fetch_assoc($jailsql)){
      $tijdgevangenisover = strtotime($row['gevangenistijdbegin']);
      $tijdgevangenisover = $tijdgevangenisover+$row['gevangenistijd'];
      $tijdgevangenisnu   = strtotime(date('Y-m-d H:i:s'));
      $tijdgevangenis     = $tijdgevangenisover-$tijdgevangenisnu;	   

      if($tijdgevangenis > 0){
        $injail++;
        if($injail == 1){
          echo'<tr>
            <td width="50" class="top_first_td">'.$txt['#'].'</td>
            <td width="100" class="top_td">'.$txt['username'].'</td>
            <td width="50" class="top_td">'.$txt['country'].'</td>
            <td width="120" class="top_td">'.$txt['time'].'</td>
            <td width="80" class="top_td">'.$txt['cost'].'</td>
            <td width="100" class="top_td">'.$txt['buy_out'].'</td>
            <td width="100" class="top_td">'.$txt['bust'].'</td>	
          </tr>';     
        }	    	

        if($_SESSION['naam'] == $row['username']){
          $kijker_injail = 1;      

          echo'<form method="post">
            	<tr>
            		<td class="normal_first_td">'.$injail.'.</td>
            		<td class="normal_td"><a href="index.php?page=profile&player='.$row['username'].'">'.$row['username'].'</a></td>
            		<td class="normal_td"><img src="images/flags/'.$row['land'].'.png"></td>
            		<td class="normal_td"><span id="minuten'.$injail.'"> </span><span id="seconden'.$injail.'"</span></td>
            		<td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> <span id="kosten'.$injail.'"></span></td>
            		<td class="normal_td"><input type="submit" name="koop" value="'.$txt['button_buy'].'" class="button_mini" id="koopuitt['.$injail.']"></td>
            		<td class="normal_td"><input type="submit" name="bust" value="'.$txt['button_bust'].'" class="button_mini" disabled></td>
            		<input type="hidden" name="id" value="'.$row['user_id'].'">
            	</tr>
          </form>';
        }	    	

        elseif($tijdgevangenisjou > 0){
          echo'<tr>
            	<td class="normal_first_td">'.$injail.'.</td>
            	<td class="normal_td"><a href="index.php?page=profile&player='.$row['username'].'">'.$row['username'].'</a></td>
            	<td class="normal_td"><img src="images/flags/'.$row['land'].'.gif"></td>
            	<td class="normal_td"><span id="minuten'.$injail.'"> </span><span id="seconden'.$injail.'"</span></td>
            	<td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> <span id="kosten'.$injail.'"></span></td>
            	<td class="normal_td">--</td>
            	<td class="normal_td">--</td>
            	<input type="hidden" name="id" value="'.$row['user_id'].'">
          	</tr>';
        }	   

        else{
          echo'<form method="post">
            	<tr>
            		<td class="normal_first_td">'.$injail.'.</td>
           			<td class="normal_td"><a href="index.php?page=profile&player='.$row['username'].'">'.$row['username'].'</a></td>
            		<td class="normal_td"><img src="images/flags/'.$row['land'].'.gif"></td>
            		<td class="normal_td"><span id="minuten'.$injail.'"> </span><span id="seconden'.$injail.'"</span></td>
           			<td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> <span id="kosten'.$injail.'"></span></td>
            		<td class="normal_td"><input type="submit" name="koop" value="'.$txt['button_buy'].'" class="button_mini" id="koopuitt['.$injail.']"></td>
            		<td class="normal_td"><input type="submit" name="bust" value="'.$txt['button_bust'].'" class="button_mini" id="bustoutt['.$injail.']"></td>
            		<input type="hidden" name="id" value="'.$row['user_id'].'">
            	</tr>
          </form>';
        }		

        echo "<script type='text/javascript'>
        var tijd".$injail." = ".$tijdgevangenis.";
        function aftellen".$injail."() {
          var time".$injail." = tijd".$injail.";
          var kosten".$injail." = 4*time".$injail.";
          var min".$injail." = time".$injail." / 60;
          var mins".$injail." = Math.floor(min".$injail.");
          var gehadmin".$injail." = mins".$injail." * 60;
          var moetnog".$injail." = time".$injail." - gehadmin".$injail.";   

          if(moetnog".$injail." < 10) var secs".$injail." = '0'+moetnog".$injail.";
          else var secs".$injail." = moetnog".$injail.";      

          tijd".$injail." -= 1;
          if(tijd".$injail." < 0){
            document.getElementById('minuten".$injail."').innerHTML = 'Vrij';
            document.getElementById('seconden".$injail."').innerHTML = '';
            document.getElementById('kosten".$injail."').innerHTML = '0,00';
            document.getElementById('koopuitt['+".$injail."+']').style.display='block';
            document.getElementById('bustoutt['+".$injail."+']').style.display='block';
          }          

          else{
            document.getElementById('minuten".$injail."').innerHTML = mins{$injail} +' mins and ';
            document.getElementById('seconden".$injail."').innerHTML = secs{$injail} + ' secs';
            document.getElementById('kosten".$injail."').innerHTML = kosten{$injail}+',00';
          }          

          setTimeout('aftellen{$injail}()', 1000);
        }
		
        setTimeout('aftellen".$injail."()', 1000);
        </script>";
      }
    }      

    if($injail == 0) echo '<strong>'.$txt['nobody_injail_1'].' '.$gebruiker['wereld'].' '.$txt['nobody_injail_2'].'</strong>';
    ?>
  </table>
</center>