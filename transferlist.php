<?php
	#include dit script als je de pagina alleen kunt zien als je ingelogd bent.
	include('includes/security.php');
	
	#Je moet rank 3 zijn om deze pagina te kunnen zien
	if($gebruiker['rank'] < 3) header("Location: index.php?page=home");
	
	$page = 'transferlist';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
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

function delete_from(pokemonid,tid){
  if(IsNumeric(pokemonid)){
    $("#"+pokemonid).hide()
    $.ajax({
      type: "GET",
      url: "ajax_call/transferlist_remove.php?pokemonid="+pokemonid
    }); 
  }
}
</script>
<script type="text/javascript" src="javascripts/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox").colorbox({width:"500", height:"260", iframe:true});
		
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
	});
</script>
        
<center>    
    <table width="660" border="0">
  <tr>
    <td><center><?php echo '<h3>'.$txt['title_text_1'].' <img src="images/icons/silver.png" title="Silver"> '.$silver.'</h3
	>
    '.$txt['title_text_2']; ?></center></td>
    <td width="109" height="122" background="images/transfers.gif"></td>
  </tr>
</table></center>
    <br />
    <?
      $aantal_pokemon = mysql_num_rows(mysql_query("SELECT `user_id` FROM `transferlijst`"));
      #Als er wel een pokemon opstaat
    	if($aantal_pokemon == 0) echo '<div class="blue"><img src="images/icons/blue.png"> Er staan momenteel geen pokemon op de transferlijst.</div>';
    	else{ 
        ?>
        
        <div class="transferlist" style="height: 30px;">
            <div class="top_number"><strong><?php echo $txt['#']; ?></strong></div>
            <div class="top_pokemonimg"><strong><?php echo $txt['pokemon']; ?></strong></div>
            <div class="top_roepnaam"><strong><?php echo $txt['clamour_name']; ?></strong></div>
            <div class="top_level"><strong><?php echo $txt['level']; ?></strong></div>
            <div class="top_price"><strong><?php echo $txt['price']; ?></strong></div>
            <div class="top_player"><strong><?php echo $txt['owner']; ?></strong></div>
            <div class="top_buy"><strong><?php echo $txt['buy']; ?></strong></div>
        </div>
          <?
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
          $tl_sql = mysql_query("SELECT pw.naam, pw.type1, pw.type2, ps.*, t.id AS tid, t.silver,t.gold, g.username AS owner FROM pokemon_wild AS pw INNER JOIN pokemon_speler AS ps ON pw.wild_id = ps.wild_id INNER JOIN transferlijst AS t ON t.pokemon_id = ps.id INNER JOIN gebruikers AS g ON ps.user_id = g.user_id WHERE (t.to_user='0' OR t.to_user='".$_SESSION['id']."' OR t.user_id='".$_SESSION['id']."') ORDER BY t.silver DESC LIMIT ".$pagina.", ".$max."");
          #Lijst opbouwen per lid gaat vanzelf
          for($j=$pagina+1; $tl = mysql_fetch_assoc($tl_sql); $j++){
            $bedrag = number_format(round($tl['silver']),0,",",".");
            $bedraggold = number_format(round($tl['gold']),0,",",".");
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

            echo '<div class="transferlist" id="'.$tl['id'].'" style="height: 35px;">
					<div class="number">'.$j.'.</div>
                	<div class="pokemonimg"><img onMouseover="showhint(\''.$popup.'\', this)" src="'.$tl['animatie'].'" alt="'.$tl['naam'].'" border="0"></div>
                	<div class="roepnaam">'.$tl['naam'].$shinystar.'</div>
                	<div class="level">'.$tl['level'].'</div>
                	<div class="price"><img src="images/icons/silver.png" title="Silver"> '.$bedrag.' / <img src="images/icons/gold.png" title="Gold"> '.$bedraggold.'</div>
                	<div class="player"><a href="?page=profile&player='.$owner.'">'.$owner.'</a></div>';

            if($_SESSION['naam'] == $owner)
              echo '<div class="buyin"><input type="image" src="images/icons/take.gif" onclick="delete_from(\''.$tl['id'].'\',\''.$tid.'\');" alt="Haal '.$tl['naam'].' van transferlijst" border="0" /></div>';
            else
              echo '<div class="buyin"><a href="?page=transferlist-box&tid='.$decodedid.'"><img src="images/icons/buy.gif" title="'.$txt['buy'].' '.$pokemon['naam'].'" border="0" /></a></div>';
			  
            echo '</div>';
          }
		  ?>
          <table width="660">
		  <?
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
      <?
      #If afsluiten, die kijkt als er wel pokemon zijn
      }
    ?>