<?php
	#include dit script als je de pagina alleen kunt zien als je ingelogd bent.
	include('includes/security.php');
	
	$page = 'transferlist';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');

	
	$tradesql = mysql_query("SELECT trade_center.*, pokemon_speler.wild_id, pokemon_speler.shiny FROM trade_center INNER JOIN pokemon_speler ON trade_center.pokemonid = pokemon_speler.id WHERE trade_center.userid = '".$_SESSION['id']."'");
	$trade = mysql_fetch_assoc($tradesql);
	
	if(isset($_POST['haalop'])){
	$select = mysql_fetch_assoc(mysql_query("SELECT * FROM `trade_center` WHERE `pokemonid`='".$_POST['pokemonid']."'"));
	$hoeveelinhand = $gebruiker['in_hand'] + 1;
	if($_SESSION['id'] != $select['userid'])
    $error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red">Er is iets fout gegaan. </font></p></div>';
	elseif($hoeveelinhand == 7)
		$error = '<div class="red"><p><img src="images/icons/alert_red.png" alt="error" /><font color="red">Je kan niet meer dan 6 Pokémon dragen. </font></p></div>';
	else{
  	mysql_query("UPDATE pokemon_speler SET `opzak`='ja', `opzak_nummer`='".$hoeveelinhand."' WHERE id = '".$_POST['pokemonid']."'");
  	mysql_query("DELETE FROM trade_center WHERE pokemonid = '".$_POST['pokemonid']."'");
	mysql_query("DELETE FROM trade_biedingen WHERE pokemonid = '".$_POST['pokemonid']."'");
	mysql_query("DELETE FROM trade_biedingen WHERE pokemonid_bieder = '".$_POST['pokemonid']."'");
  	
	$error = '<div class="green"><p><img src="images/icons/alert_green.png" alt="error" /><font color="green"> Pokémon Handel wurde abgebrochen. </font></p></div>';
	}
}
	
	
?>

<script type='text/javascript'>
function IsNumeric(sText){
  var ValidChars = "0123456789";
  var IsNumber=true;
  var Char;
  
  for (i = 0; i < sText.length && IsNumber == true; i++){ 
    Char = sText.charAt(i); 
    if (ValidChars.indexOf(Char) == -1) IsNumber = false;
  }
  return IsNumber;
}
</script>
<script type="text/javascript" src="javascripts/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox").colorbox({width:"500", height:"260", iframe:true});
		
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$('#click').css({"background-color":"#f00", "color":"#fff"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
	});
</script>
        
<center>    
    <table width="660" border="0">
  <tr>
    <td><center><?php echo '<h3>Welkom bij het Tradecenter.</h3>'; ?><br/>
       Je kan hier Pokémon plaatsen welke je zou willen ruilen met andere spelers.<br/>
	   Voeg een Pokémon hier toe <br/><br/><br/><a class= "button_mini" href="?page=trade-add">Toevoegen</a>
	</center></td>
    <td width="109" height="122" background="images/transfers.gif"></td>
  </tr>
</table></center>
    <br />
    <?php
      $aantal_pokemon = mysql_num_rows(mysql_query("SELECT `userid` FROM `trade_center`"));
      #Als er wel een pokemon opstaat
    	if($aantal_pokemon == 0) echo '<div class="blue"><img src="images/icons/blue.png">Er zijn geen Pokémon op het Tradecenter.</div>';
    	else{ 
        ?>
        
			<table width="100%" cellpadding="0" cellspacing="0">
            	<tr>
					<td width="80" class="top_first_td"><p><?php echo '#'; ?></p></td>
                    <td width="110" class="top_td"><p><?php echo 'Naam'; ?></p></td>
                    <td width="110" class="top_td"><p><?php echo 'Level'; ?></p></td>
                    <td width="80" class="top_td"><p><?php echo 'Eigenaar'; ?></p></td>
					<td width="80" class="top_td"><p><?php echo 'Biedingen'; ?></p></td>
				</tr>
          <?php
          if(empty($_GET['subpage'])) $subpage = 1; 
          else $subpage = $_GET['subpage']; 
          
          #Max level bereken dat een speler kan kopen
          $maxlevel = $gebruiker['rank']*5;
		  		#Max aantal pokemon per pagina
          $max = 50; 
          $aantal_paginas = ceil($aantal_pokemon/$max); 
          if($aantal_paginas == 0) $aantal_paginas = 1;   
          $pagina = $subpage*$max-$max; 
          
          #Gegevens laden voor de lijst 
          $tl_sql = mysql_query("SELECT pw.naam, pw.type1, pw.type2, ps.*, t.trade_id AS tid, g.username AS owner FROM pokemon_wild AS pw INNER JOIN pokemon_speler AS ps ON pw.wild_id = ps.wild_id INNER JOIN trade_center AS t ON t.pokemonid = ps.id INNER JOIN gebruikers AS g ON ps.user_id = g.user_id WHERE ps.opzak='trc' ORDER BY t.trade_id DESC LIMIT ".$pagina.", ".$max."") OR DIE(mysql_error());
          #Lijst opbouwen per lid gaat vanzelf
          for($j=$pagina+1; $tl = mysql_fetch_assoc($tl_sql); $j++){
            $owner = $tl['owner'];
			      $decodedid = base64_encode($tl['tid']);
			      $tid = $tl['tid'];

          	#Gegevens juist laden voor de pokemon
          	$tl = pokemonei($tl);
          	#Naam veranderen als het male of female is.
          	$tl['naam'] = pokemon_naam($tl['naam'],$tl['roepnaam']);
      			#popup
      			$popup = pokemon_popup($tl, $txt);
      			
      			#$link      = "pokemon/icon/".$naamklein.".gif";
    			  $shinystar = '';
    			  $pokemontype = $tl['type1'];
			
            #Heeft pokemon meerdere types
            if(!empty($tl['type2']))
              $pokemontype = $tl['type1']."-".$tl['type2'];

            if($tl['shiny'] == 1){
              #$link      = "shiny/icon/".$naamklein.".gif";
			        $shinystar = '<img src="images/icons/lidbetaald.png" width="16" height="16" style="margin-bottom:-3px;" border="0" alt="Shiny" title="Shiny">';
            }

            echo '<tr>
			<td class="normal_first_td"><img onMouseover="showhint(\''.$popup.'\', this)" src="'.$tl['animatie'].'" alt="'.$tl['naam'].'" border="0"></td>
      			<td class="normal_td"><p>'.$tl['naam'].$shinystar.'</p></td>
      			<td class="normal_td"><p>Lvl. '.$tl['level'].'</p></td>
				<td class="normal_td"><p><a href="?page=profile&player='.$owner.'">'.$owner.'</a></p></td>';
			
			
            if($_SESSION['naam'] == $owner)
              echo '<td class="normal_td"><p><form method="post"><input type="hidden" name="pokemonid" class="button_mini" value="'.$trade['pokemonid'].'"><button type="submit" name="haalop" class="button">Intrekken</button></form></p></td>';
            else
              echo '<td class="normal_td"><p>
			  
			  <a  href="?page=trade-box&id='.$tl['id'].'"> <img src="/images/icons/buy.gif" title="bieden"/></a>
			  </p></td>';
			  
            echo '</tr>';
          }
		  ?>
		  </table>
          <table width="660">
		  <?php
         #Pagina systeem
          $links = false;
          $rechts = false;
          echo '<tr><td><center><br /><div class="sabrosus">';
          if($subpage == '1'){
            echo '<span class="disabled"> &lt; </span>';
          }
          else{
            $back = $subpage-1;
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'"> &lt; </a>';
          }
          for($i = 1; $i <= $aantal_paginas; $i++) 
          { 
              
            if((2 >= $i) && ($subpage == $i)){
              echo '<span class="current">'.$i.'</span>';
            }
            elseif((2 >= $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage == $i)){
              echo '<span class="current">'.$i.'</span>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
            }
            else{
              $max = $subpage+3;
              $min = $subpage-3;  
              if($page == $i){
                echo '<span class="current">'.$i.'</span>';
              }
              elseif(($min < $i) && ($max > $i)){
              	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
              }
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
          if($aantal_paginas == $subpage){
            echo '<span class="disabled"> &gt; </span>';
          }
          else{
            $next = $subpage+1;
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'"> &gt; </a>';
          }
          echo "</div></center></td></tr>";
  	    
          ?>
        </table>
      <?php
      #If afsluiten, die kijkt als er wel pokemon zijn
      }
    ?>