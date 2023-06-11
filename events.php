<?php 
	#Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");	
	
	$page = 'events';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>

<script language="javascript">
var checked = 0;
function checkAll() {
  checked = !checked;
  for(i=0; i<document.gebeurtenis.elements.length; i++)
    document.gebeurtenis.elements[i].checked = checked;
}
</script>
<form method="post" name="gebeurtenis">
      <?
      #Alle ongelezen gebeurtenisse op gelezen zetten
      if($event_new > 0) mysql_query("UPDATE `gebeurtenis` SET `gelezen`='1' WHERE `ontvanger_id`='".$_SESSION['id']."'");
	  
	  if($gebruiker['premiumaccount'] > 0) $toegestaan = 50;
	  else $toegestaan = 25;
	  
	  #Gebeurtenissen wissen wat niet in de lijst staan
	  $hoeveel = mysql_fetch_assoc(mysql_query("SELECT COUNT(id) AS nu FROM gebeurtenis WHERE ontvanger_id = '".$_SESSION['id']."'"));
	  if($toegestaan < $hoeveel['nu']){
		  $delete = $hoeveel['nu']-$toegestaan;
		  mysql_query("DELETE FROM gebeurtenis WHERE ontvanger_id='".$_SESSION['id']."' ORDER BY id ASC LIMIT ".$delete."");
	  }

      if(isset($_POST['verwijder'])){
        #teller starten
        $teller = 0;
        #Als er wel een post id is
        if(isset($_POST['eventid'])){
          foreach($_POST['eventid'] as $eventid) {
            #Alle ontvangen id's verwijderen
            mysql_query("DELETE FROM `gebeurtenis` WHERE `id`='".$eventid."' AND `ontvanger_id`='".$_SESSION['id']."'");
            if($eventid >= 1) $geselecteerd = true;
            $teller++;
          }
        }
        #Als er geen post id is deze error
        else
          echo '<div class="red">'.$txt['alert_nothing_selected'].'</div><br/><br/>';
        
        #Tekst opstellen
        $tekst = $txt['alert_more_events_deleted'];
        if($teller == 1)
          $tekst = $txt['alert_one_event_deleted'];

        #Als er berichten verwijderd zijn
        if ($geselecteerd)  echo '<div class="green">'.$tekst.'</div><br/><br/>';
      }
      
      echo '<center><table width="660" cellpadding="0" cellspacing="0">
  			<tr>
			  <td width="30" class="top_first_td"><input type="checkbox" onClick="checkAll()"></td>
			  <td width="130" class="top_td">'.$txt['date-time'].'</td>
			  <td width="500" class="top_td">'.$txt['event'].'</td>
			</tr>';
      
      #Gegevens laden
      $event_sql = mysql_query("SELECT `id`, `datum`, `ontvanger_id`, `bericht`, `gelezen` FROM `gebeurtenis` WHERE `ontvanger_id`='".$_SESSION['id']."' and `type` NOT LIKE 'catch' ORDER BY `id` DESC LIMIT 0 , ".$toegestaan."");
      $event_count = mysql_num_rows($event_sql);
	  
      #Lijst opbouwen per bericht gaat vanzelf
      while($events = mysql_fetch_assoc($event_sql)){ 
	  #Datum mooi fixen
	  $datum = explode("-", $events['datum']);
  	  $tijd = explode(" ", $datum[2]);
 	  $datum = $tijd[0]."-".$datum[1]."-".$datum[0].",&nbsp;".$tijd[1];
  	  $datum_finished = substr_replace($datum ,"",-3);
	  
        echo '<tr>
				<td class="normal_first_td"><input type="checkbox" name="eventid[]" value="'.$events['id'].'"></td>
				<td class="normal_td">'.$datum_finished.'</td>
				<td class="normal_td">'.$events['bericht'].'</td>
			  </tr>';
      }
      if($event_count == 0){
        echo '<tr>
				<td colspan="3" class="normal_first_td">'.$txt['no_events'].'</td>
			  </tr>';
      }
      
      ?>
      </tr>
      <tr>
      <td colspan="3"><div style="padding-top:15px;"><button type="submit" name="verwijder" class="button"><?php echo $txt['button']; ?></button></div></td>
    </tr> 
  </table>
  </form>
</center>