<?php
	//include dit script als je de pagina alleen kunt zien als je ingelogd bent.
	include('includes/security.php');

	//Je moet rank 3 zijn om deze pagina te kunnen zien
	if($gebruiker['rank'] < 3) header("Location: index.php?page=home");
	
	$page = 'sell';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>

<script type="text/javascript" src="javascripts/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox").colorbox({width:"500", height:"330", iframe:true});
					
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
		$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
	});
</script>

<?
//Pagina nummer opvragen
if(empty($_GET['subpage'])) $subpage = 1; 
else $subpage = $_GET['subpage']; 
//Max aantal leden per pagina
$max = 50; 

$aantal = mysql_num_rows(mysql_query("SELECT `id` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND (`opzak`='nee' OR `opzak`='tra')"));
$aantal_paginas = ceil($aantal/$max); 

$count = mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `user_id`='".$_SESSION['id']."'"));

if($aantal_paginas == 0) $aantal_paginas = 1;

$pagina = $subpage*$max-$max; 
$allowed = 1;
if($gebruiker['premiumaccount'] >= 1) $allowed = 3;

?>
<center>
<?php echo $txt['title_text_1']; ?> <strong><?php echo $allowed; ?></strong> <?php echo $txt['title_text_2']; ?> <strong><? echo $count; ?></strong> <?php echo $txt['title_text_3']; ?><br /><br />
  <table width="390" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
      <td width="90" class="top_td"><?php echo $txt['pokemon']; ?></td>
      <td width="120" class="top_td"><?php echo $txt['clamour_name']; ?></td>
      <td width="60" class="top_td"><?php echo $txt['level']; ?></td>
      <td width="70" class="top_td"><center><?php echo $txt['sell']; ?></center></td>
    </tr>
    <?
	if($aantal == 0){
		echo '<tr>
				<td colspan="5" class="normal_td">'.$txt['no_pokemon_in_house'].'</td>
			  </tr>';
	}
	else{
    $poke = mysql_query("SELECT pokemon_speler.*, pokemon_wild.naam, pokemon_wild.type1, pokemon_wild.type2
							   FROM pokemon_speler
							   INNER JOIN pokemon_wild
							   ON pokemon_speler.wild_id = pokemon_wild.wild_id
							   WHERE pokemon_speler.user_id='".$_SESSION['id']."' AND (pokemon_speler.opzak = 'nee' OR pokemon_speler.opzak = 'tra')  ORDER BY pokemon_wild.naam ASC LIMIT ".$pagina.", ".$max."");
  
    //Teller op 0 zetten
    $telleropzak = 0;
    $tellersell = 0;
    
    for($j=$pagina+1; $pokemon = mysql_fetch_array($poke); $j++){
      //Alle pokemons tellen
      $tellersell++;
      $opzakcheck = $pokemon['opzak'];
      //Gegevens juist laden voor de pokemon
      $pokemon = pokemonei($pokemon);
      //Naam veranderen als het male of female is.
      $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
      $popup = pokemon_popup($pokemon, $txt);
      
	   //Als pokemon geen baby is
      echo '
        <tr>
          <td class="normal_first_td">'.$tellersell.'.</td>
          <td class="normal_td"><a href="#" class="tooltip" onMouseover="showhint(\''.$popup.'\', this)"><img src="'.$pokemon['animatie'].'" width=32 height=32></a></td>
          <td class="normal_td">'.$pokemon['naam'].$shinystar.'</td>
          <td class="normal_td">'.$pokemon['level'].'</td>';
      
      if($opzakcheck == 'tra')
        echo' <td class="normal_td"><center><a href="?page=transferlist"><img src="images/icons/on-transferlist.gif" title="'.$txt['go_to_transferlist'].'" border="0" /></a></center></td>'; 
      else
        echo' <td class="normal_td"><center><a href="?page=sell-box&id='.$pokemon['id'].'"><img src="images/icons/sell.gif" title="'.$txt['menu_sell'].' '.$pokemon['naam'].'" border="0" /></center></a></td>';
      echo" </tr>";
    }
	}
           
    //Pagina systeem
    $links = false;
    $rechts = false;
    echo '<tr><td colspan=5><center><br /><div class="sabrosus">';
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
    echo "</div></center></td>";
  
    ?>
  </table>
</center>