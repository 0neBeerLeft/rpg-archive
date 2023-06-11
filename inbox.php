<?php 
	#Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'inbox';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');

    #Pagina nummer opvragen
    if(empty($_GET['subpage'])) $subpage = 1; 
    else $subpage = $_GET['subpage'];   
    #Max berichten leden per pagina
    $max = 30; 
    #pagina's tellen        
    $pagina = $subpage*$max-$max; 
    #Er moet sowieso een aantal zijn
    if($inbox == 0) $inbox = 1;
    #Berekenen hoeveel pagina's er zijn
    $aantal_paginas = ceil($inbox/$max);
    
    if($subpage == $aantal_paginas) $new_page = $subpage-1;
    else $new_page = $subpage;
	
	#Meldingen
	echo '<div id="fail" class="red" style="display:none;">'.$txt['alert_nothing_selected'].'</div>';
	echo '<div id="succes" class="green" style="display:none;">'.$txt['success_deleted'].'</div>';
?>

<center>
<table width="660" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10" class="top_first_td"><input type="checkbox" onClick="check_all(this)"></td>
      <td width="80" class="top_td"><center><?php echo $txt['new_check']; ?></center></td>
      <td width="170" class="top_td"><?php echo $txt['subject']; ?></td>
      <td width="160" class="top_td"><?php echo $txt['username']; ?></td>
      <td width="120" class="top_td"><?php echo $txt['status']; ?></td>
      <td width="130" class="top_td"><?php echo $txt['date-time']; ?></td>
    </tr>
    <?
    #Berichten laden die de gebruiker heeft ontvangen
    $select = mysql_query("SELECT berichten.*, gebruikers.username, gebruikers.premiumaccount, gebruikers.online
						   FROM berichten
						   LEFT JOIN gebruikers
						   ON berichten.afzender_id = gebruikers.user_id
						   WHERE `ontvanger_id`='".$_SESSION['id']."' ORDER BY `id` DESC LIMIT ".$pagina.", ".$max."");
	$count_inbox = mysql_num_rows($select);
	
    #Lijst opbouwen per bericht gaat vanzelf
    for($j=$pagina+1; $inbox = mysql_fetch_assoc($select); $j++){ 
      #code encoderen
      $link = base64_encode($inbox['id']."/".$inbox['ontvanger_id']."/".$inbox['afzender_id']."/".$inbox['onderwerp']);
	  #Datum mooi fixen
	  $datum = explode("-", $inbox['datum']);
  	  $tijd = explode(" ", $datum[2]);
 	  $datum = $tijd[0]."-".$datum[1]."-".$datum[0].",&nbsp;".$tijd[1];
  	  $datum_finished = substr_replace($datum ,"",-3);

      #Als de mail nog niet gezelen is, de mail laten opvallen
      if($inbox['gelezen'] == 0){
        $plaatje   = "images/icons/berichtongelezen.png";
        $onderwerp = "<b>".$inbox['onderwerp']."</b>";
      }
	  else{
		  $plaatje   = "images/icons/berichtgelezen.png";
		  $onderwerp = $inbox['onderwerp'];
	  }
	  
	  #premium things
	  if($inbox['premiumaccount'] > 0){
		  $premstar = '<img src="images/icons/lidbetaald.png" width="16" height="16" alt="Premium" title="Premium" class="imglower">';
	  }
	  else $premstar = '';
	  
	  #Online Offline status
      if(($inbox['online']+300) > time()){
          $status = "images/icons/status_online.png";
          $online  = $txt['online'];
      }
	  else{
		  $status = "images/icons/status_offline.png";
		  $online = $txt['offline'];
	  }

      echo '<tr>
			  <td class="normal_first_td"><input type="checkbox" name="id[]" value="'.$inbox['id'].'"></td>
			  <td class="normal_td"><a href="?page=read-message&code='.$link.'"><center><img src="'.$plaatje.'" alt="" border="0"></center></a></td>
			  <td class="normal_td"><a href="?page=read-message&code='.$link.'">'.$onderwerp.'</a></td>
			  <td class="normal_td"><a href="?page=profile&player='.$inbox['username'].'">'.$inbox['username'].$premstar.'</a></td>
			  <td class="normal_td"><img src="'.$status.'" width="18" height="15" class="imglower" />'.$online.'</td>
			  <td class="normal_td">'.$datum_finished.'</td>
			</tr>';
    }
    
    if($count_inbox == 0){
      echo '<tr>
			  <td colspan="6" class="normal_first_td">'.$txt['no_messages'].'</td>
			</tr>';
    }
        
    #Pagina systeem
    $links = false;
    $rechts = false;
    echo '<tr><td colspan="3"><div style="margin-top:15px"></div></td>
			  <td colspan="3"><div style="margin:15px 0 0 -200px"><div class="sabrosus"> <button id="delete" style="float:right;" class="button_mini"/>'.$txt['button'].'</button> <a href="?page=send-message" style="float:right;" class="button_mini">Nieuw bericht</a> ';
    if($subpage == 1)
      echo '<span class="disabled"> &lt; </span>';
    else{
      $back = $subpage-1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'"> &lt; </a>';
    }
    for($i = 1; $i <= $aantal_paginas; $i++) { 
      if((2 >= $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif((2 >= $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
      elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
      else{
        $max = $subpage+3;
        $min = $subpage-3;  
        if($subpage == $i)
          echo '<span class="current">'.$i.'</span>';
        elseif(($min < $i) && ($max > $i))
        	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
        else{
          if($i < $subpage){
            if(!$links){
              echo '...';
              $links = True;
            }
          }
          else{
            if(!$rechts){
              echo '...';
              $rechts = True;
            }
          }
        }
      }
    } 
    if($aantal_paginas == $subpage)
      echo '<span class="disabled"> &gt; </span>';
    else{
      $next = $subpage+1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'"> &gt; </a>';
    }
    echo "</div></div></td>";
    ?>
    </tr>
  </table>
</center>
<script language="javascript">
function check_all(iam){ 
  var list = document.getElementsByTagName('input'); 
  for (var i = 0; i < list.length; i++){ 
    var node = list[i]; 
    if (node.getAttribute('type') == 'checkbox'){ 
      if (iam.checked == 1) node.checked = 1;
      else node.checked = 0;
    } 
  } 
}

$("button[id='delete']").click(function(){
  var arr = Array()
  var aantal = 0;
  var list = document.getElementsByTagName('input');
  for (var i = 0; i < list.length; i++){ 
    var node = list[i]
    if (node.getAttribute('type') == 'checkbox'){ 
      if (node.checked == 1){
        arr.push(node.getAttribute('value'))
        aantal++;
      }
    } 
  } 
  
  $.get("ajax_call/inbox-del.php?id="+arr+"&sid="+Math.random(), function(data) {
    if(data == "succes"){
		  if(i == aantal) setTimeout("location.href='index.php?page=inbox&subpage=<? echo $new_page; ?>'", 0)
		  else setTimeout("location.href='index.php?page=inbox&subpage=<? echo $subpage; ?>'", 0)
		  $("#succes").show()
    }
    else if(data == "fail"){
      $("#fail").show()
      $("#fail").hide(1000);
    }
  });
});
</script>