<script>
var deg = 2; // starting
var rotation_diff = 30;

var rotation;

start();

function start()
{
	rotation=1;    
	myFunction();
}

function stop()
{
	rotation=0;    
}

function myFunction() {
	var img = document.getElementById("image");

	img.style.webkitTransform = "rotate(" + deg + "deg)";
	img.style.transform = "rotate(" + deg + "deg)";
	img.style.MozTransform = "rotate(" + deg + "deg)";
	img.style.msTransform = "rotate(" + deg + "deg)";
	img.style.OTransform = "rotate(" + deg + "deg)";

	if (rotation == 1) {

		setTimeout("myFunction()", 150);
	}
	deg = deg + rotation_diff;
}
</script>

<? 
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'wheel-of-fortune';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if($gebruiker['item_over'] < 1)
	echo '<div class="blue">'.$txt['alert_itemplace'].'</div>';

// gemaakt doar Marieke  
if(isset($_POST['draai'])){
	$getal = rand(1,8);
  //Als geluksrad al gedaan is
  if($gebruiker['geluksrad'] == 0)
    $melding = '<div class="blue">'.$txt['alert_no_more_wof'].'</div>';
  //Is het nog niet gedaan.
  else{
  	#WIN: 100 silver
  	if($getal == 1){
  		$melding = '<div class="green">'.$txt['win_100_silver'].'</div>';	
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1', silver=silver+'100' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
  	}
  	#WIN: 250 silver
  	elseif($getal == 2){
  		$melding ='<div class="green">'.$txt['win_250_silver'].'</div>';
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1', silver=silver+'250' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
  	}
  	#WIN: Ball
  	elseif($getal == 3){
      if($gebruiker['item_over'] > 0){
        $ball = mysql_fetch_assoc(mysql_query("SELECT naam FROM markt WHERE soort = 'balls' AND naam != 'Master ball' ORDER BY rand() limit 1"));
    	$melding = '<div class="green">'.$txt['win_ball'].' '.$ball['naam'].'!</div>';
  		  mysql_query("UPDATE gebruikers_item SET `".$ball['naam']."`=`".$ball['naam']."`+'1' WHERE user_id='".$_SESSION['id']."'");
		  mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1' WHERE user_id='".$_SESSION['id']."'");
		  $gebruiker['geluksrad']--;
		  }
		  else $melding = '<div class="red">'.$txt['alert_itembox_full'].'</div>';
  	}
  	#LOST: Silver.
  	elseif($getal == 4){
  		$melding = '<div class="red"><img src="images/icons/red.png" width="16" height="16" /> '.$txt['lose_fortune_silver'].'</div>';
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1', silver=silver-100 WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
  	}	
  	#WIN: Special Item
  	elseif($getal == 5){
      if($gebruiker['item_over'] > 0){
        $specialitem = mysql_fetch_assoc(mysql_query("SELECT naam FROM markt WHERE soort = 'special items' AND naam != 'Rare candy' ORDER BY rand() limit 1"));
        $melding = '<div class="green">'.$txt['win_spc_item'].' '.$specialitem['naam'].'!</div>'; 
        mysql_query("UPDATE gebruikers_item SET `".$specialitem['naam']."`=`".$specialitem['naam']."`+'1' WHERE user_id='".$_SESSION['id']."'");
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
      }
      else $melding = '<div class="red">'.$txt['alert_itembox_full'].'</div>';
  	}
    #WIN: Evolutie Stone
  	elseif($getal == 6){
      if($gebruiker['item_over'] > 0){
        $stone = mysql_fetch_assoc(mysql_query("SELECT naam FROM markt WHERE soort = 'stones' ORDER BY rand() limit 1"));
        $melding = '<div class="green">'.$txt['win_stone'].' '.$stone['naam'].'!</div>'; 
        mysql_query("UPDATE gebruikers_item SET `".$stone['naam']."`=`".$stone['naam']."`+'1' WHERE user_id='".$_SESSION['id']."'");
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
      }
      else $melding = '<div class="red">'.$txt['alert_itembox_full'].'</div>';
  	}
	#WIN: 5 gold
  	elseif($getal == 7){
  		$melding ='<div class="green">'.$txt['win_5_gold'].'</div>';
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1', gold=gold+'5' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
  	}
    #WIN: TM
  	elseif($getal == 8){
      if($gebruiker['item_over'] > 0){
        $tm = mysql_fetch_assoc(mysql_query("SELECT naam FROM markt WHERE soort = 'tm' ORDER BY rand() limit 1"));
        $melding = '<div class="green">'.$txt['win_tm'].' '.$tm['naam'].'!</div>'; 
        mysql_query("UPDATE gebruikers_tmhm SET `".$tm['naam']."`=`".$tm['naam']."`+'1' WHERE user_id='".$_SESSION['id']."'");
		mysql_query("UPDATE gebruikers SET geluksrad=geluksrad-'1' WHERE user_id='".$_SESSION['id']."'");
		$gebruiker['geluksrad']--;
      }
      else $melding = '<div class="red">'.$txt['alert_itembox_full'].'</div>';
  	}
	}
}
if(isset($melding) && !empty($melding)) echo $melding;
?>
<center>
  <table width="100%" border="0">
    <tr><td><center><?php echo $txt['title_text_1'].' <strong>'.$gebruiker['geluksrad'].'</strong> '.$txt['title_text_2']; ?>
					<?php if($gebruiker['premiumaccount'] == 0) echo $txt['premiumtext']; ?></center><br/><br/></td>
    </tr>
    <tr> 
      <td align="center"><img id="image" src="images/geluksrad.png" width="150"></td>
    </tr>
      <tr> 
        <td style="padding: 20px;"><center><form id="geluksrad" name="geluksrad" method="post"><button type="submit" id="draai" name="draai" class="button"><?php echo $txt['button']; ?></form></form></center></td>
      </tr>
  </table>        
</center> 
<?
if(isset($_POST['draai'])) {
?>
<script>
	window.onload = start();
	setTimeout("stop()", 1500);
</script>
<?
}
?>