<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

if(($gebruiker['rank'] < 5) && ($_GET['shopitem'] == 'attacks')) header('Location: index.php?page=pokemarket');

$page = 'market';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Kijken of je nog wel een itemplek overhebt
if($gebruiker['item_over'] < 1){
	echo '<div class="blue">'.$txt['alert_itemplace'].'</div>';
}
?>
<?
  #Ruimte vast stellen Per item.
  #Vast stellen welke items nog te koop zijn.
  if($gebruiker['itembox'] == 'Red box') $ruimte['max'] = 250;
  elseif($gebruiker['itembox'] == 'Blue box') $ruimte['max'] = 100;
  elseif($gebruiker['itembox'] == 'Yellow box') $ruimte['max'] = 50;
  elseif($gebruiker['itembox'] == 'Bag') $ruimte['max'] = 20;
  
  #Pagina's opbouwen
  switch ($_GET['shopitem']) {
    #Als er op balls geklikt word. Het volgende laten zien
	  case "balls":
      $sql = mysql_query("SELECT `id`, `naam`, `silver`, `gold`, `omschrijving_".GLOBALDEF_LANGUAGE."` FROM `markt` WHERE `soort`='balls'");
  	  #Als er op de knop gedrukt word
      if(isset($_POST['balls'])){
        $gebruiker_silver = $gebruiker['silver'];
		$gebruiker_gold = $gebruiker['gold'];
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++){
          #Item id opvragen
          $itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
          #Als er geen aantal is
          if(empty($aantal)) $niksingevoerd = True;
          elseif(!is_numeric($aantal)) $niksingevoerd = True;
          #Als er wel een aantal is
          elseif(!empty($aantal)){
            #Item gegevens laden
            $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver`, `gold` FROM `markt` WHERE `id`='".$itemid."'"));
			#silver of gold berekenen voor de balls
			if($itemgegevens['naam'] == 'Master ball') $goldd = $aantal*($itemgegevens['gold']/1);
            else $silverr = $aantal*($itemgegevens['silver']/1);
            #itemruimte over berekenen
            $ruimteover = $ruimte['max']-$gebruiker['items'];
            #Kijken als het silver er wel voor is
            if(($gebruiker_silver < $silverr) OR ($gebruiker_gold < $goldd)){
              echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            }
			elseif($aantal < 0)
    			echo'<div class="red">'.$txt['alert_not_enough_money'].'</div>';
			elseif(!ctype_digit($aantal))
  				echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            #Als speler niet genoeg ruimte heeft voor de balls
            elseif($ruimteover < $aantal){
              if($aantal > 1) $netheid = "&#39;s";
              echo '<div class="red">'.$txt['alert_itembox_full_1'].' '.$ruimteover.' '.$itemgegevens['naam'].$netheid.' '.$txt['alert_itembox_full_2'].'</div>';
            }
            else{
              #Opslaan
              $totalesilver += $silverr;
              $gebruiker_silver -= $silverr;
              $totalegold += $goldd;
              $gebruiker_gold -= $goldd;

              mysql_query("UPDATE `gebruikers_item` SET `".$itemgegevens['naam']."`=`".$itemgegevens['naam']."`+'".$aantal."' WHERE `user_id`='".$_SESSION['id']."'");

              echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' '.$aantal.'x gekocht.</div>';
            }
            $welingevoerd = True;
          }
    	  }
    	  #silver opslaan
    	  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$totalesilver."', gold=gold-'".$totalegold."' WHERE `user_id`='".$_SESSION['id']."'");
    	  if(!$welingevoerd){
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
        }
      }
?>

<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
  	  <?php #Form starten
  	  echo '<form method="POST" name="balls">
	  <tr>
	  	<td>';
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
  	    if($select['naam'] == "Master ball"){
			$icon = 'gold';
			$prijs = number_format(round($select['gold']),0,",",".");
			
		}
		else{
			$icon = 'silver';
    		$prijs = number_format(round($select['silver']),0,",",".");
		}
          echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="'.$j.'">
				<input type="hidden" name="id'.$j.'" value="'.$select['id'].'">
				<img src="images/items/'.$select['naam'].'.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/'.$icon.'.png" alt="'.$icon.'" title="'.$icon.'" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="text" maxlength="2" class="text_short" name="aantal'.$select['id'].'" placeholder="0" style="float:none;" /></center></div></td>
			</tr>
		  </table>
		  </div>';
        }
	  echo '</td></tr>';
		?>
      <tr>
        <td><div style="padding-left:10px;"><button type="submit" name="balls" class="button"><?php echo $txt['button_balls']; ?></button></div></td>
      </tr>
    </form>
    
		<?
	  #Einde balls
	  break;
		#Als er op potions geklikt word, het volgende laten zien
		case "potions":
		 $sql     = mysql_query("SELECT `id`, `naam`, `silver`,`gold`, `omschrijving_".GLOBALDEF_LANGUAGE."` FROM `markt` WHERE `soort`='potions'");
  	  #Als er op de knop gedrukt word
      if(isset($_POST['potions'])){
        $gebruiker_silver = $gebruiker['silver'];
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++){
          #Item id opvragen
          $itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
          #Als er geen aantal is
          if(empty($aantal)) $niksingevoerd = True;
          elseif(!is_numeric($aantal)) $niksingevoerd = True;
          #Als er wel een aantal is
          elseif(!empty($aantal)){
            $niksingevoerd = False;
            #Gegevens laden van de te kopen item
            $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver` FROM `markt` WHERE `id`='".$itemid."'"));
            #Prijs bereken voor het aantal potions.
            $silverr = $aantal*($itemgegevens['silver']/1);
            #itemruimte over berekenen
            $ruimteover = $ruimte['max']-$gebruiker['items'];
            #Kijken als er wel genoeg silver is.
            if($gebruiker_silver < $silverr){
              echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            }
			elseif($aantal < 0)
    			echo'<div class="red">'.$txt['alert_not_enough_money'].'</div>';
			elseif(!ctype_digit($aantal))
  				echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            #Kijken als speler nog wel ruimte heeft voor de potions
            elseif($ruimteover < $aantal){
              if($aantal > 1) $netheid = "&#39;s";
              echo '<div class="red">'.$txt['alert_itembox_full_1'].' '.$ruimteover.' '.$itemgegevens['naam'].$netheid.' '.$txt['alert_itembox_full_2'].'</div>';
            }
            else{
              #Opslaan
              $totalesilver += $silverr;
              $gebruiker_silver -= $silverr;
              mysql_query("UPDATE `gebruikers_item` SET `".$itemgegevens['naam']."`=`".$itemgegevens['naam']."`+'".$aantal."' WHERE `user_id`='".$_SESSION['id']."'");
              echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' '.$aantal.'x gekocht.</div>';
            }
            $welingevoerd = True;
          }
    	  }
    	  #silver opslaan
    	  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$totalesilver."' WHERE `user_id`='".$_SESSION['id']."'");
    	  #Als wel ingevoerd een waarde heeft/true is
    	  if(!$welingevoerd){
    	    #Als niksingevoerd TRUE is
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
        }
      }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
  <?php #Form starten
  	  echo '<form method="POST" name="potions">
	  		<tr>
			  <td>';
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
  	    $prijs = number_format(round($select['silver']),0,",",".");
          echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="'.$j.'">
				<input type="hidden" name="id'.$j.'" value="'.$select['id'].'">
				<img src="images/items/'.$select['naam'].'.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/silver.png" alt="Silver" title="Silver" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="text" maxlength="2" class="text_short" name="aantal'.$select['id'].'" placeholder="0" style="float:none;" /></center></div></td>
			</tr>
		  </table>
		  </div>';
      }
	  echo'</td></tr>';
	  ?>
      <tr>
        <td><div style="padding-left:10px;"><button type="submit" name="potions" class="button"><?php echo $txt['button_potions']; ?></button></div></td>
      </tr>
    </form>  
          
	  <?
	  #Potions sluiten
		break;
    #Als er op items geklikt word. Het volgende laten zien 
		case "items": 

		  $sql     = mysql_query("SELECT `id`, `naam`, `silver`,`gold`, `omschrijving_".GLOBALDEF_LANGUAGE."` FROM `markt` WHERE `soort`='items' AND `naam` != 'Bag' AND `naam` != 'Badge case'");
  		#Als er op de knop gedrukt word
      if(isset($_POST['items'])){
        #Gegevens laden van het item
        $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver`,`gold` FROM `markt` WHERE `naam`='".$_POST['productnaam']."'"));
        #Als er niks aangvinkt is.
        if(empty($_POST['productnaam'])) $niksingevoerd = True;
        #heeft speler nog geen pokedex maar wil het wel de chip kopen?
        elseif(($gebruiker['Pokedex'] == 0) AND ($itemgegevens['naam'] == "Pokedex chip")){
          $welingevoerd = False;
          echo '<div class="blue">'.$txt['alert_pokedex_chip'].'</div>';
        }
        elseif(($gebruiker['Pokedex'] == 0) AND ($itemgegevens['naam'] == "Pokedex zzchip")){
          $welingevoerd = False;
          echo '<div class="blue">'.$txt['alert_pokedex_chip'].'</div>';
        }
        elseif($itemgegevens['naam'] == 'Pokedex zzchip' and $gebruiker['gold'] < $itemgegevens['gold']){
          $welingevoerd = False;
          echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
        }
        #Heeft speler niet genoeg silver?
        elseif($gebruiker['silver'] < $itemgegevens['silver'] and $itemgegevens['naam'] != 'Pokedex zzchip'){
          $welingevoerd = False;
          echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
        }
        #Alles is goed
        else{
          $welingevoerd = True;
          $type = explode(" ", $itemgegevens['naam']);
          #Kijken als het te kopen type een box is
          if($type[1] == "box")
            mysql_query("UPDATE `gebruikers_item` SET `itembox`='".$itemgegevens['naam']."' WHERE `user_id`='".$_SESSION['id']."'");
          #Het is geen box
          else{
            #Is er geen ruimte voor het te kopen item?
            if($ruimte['max'] <= $gebruiker['items']){
              $welingevoerd = False;
              $itemboxvol = True;
            }
            #Er is wel ruimte en dus opslaan
            else
              mysql_query("UPDATE `gebruikers_item` SET `".$itemgegevens['naam']."`='1' WHERE `user_id`='".$_SESSION['id']."'");
          }
          #Als itembox niet vol is
          if(!$itemboxvol){
            #Opslaan
            if($itemgegevens['gold'] != 0){
              mysql_query("UPDATE `gebruikers` SET `gold`=`gold`-'".$itemgegevens['gold']."' WHERE `user_id`='".$_SESSION['id']."'");
            }else{
              mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$itemgegevens['silver']."' WHERE `user_id`='".$_SESSION['id']."'");
            }
            echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' gekocht.</div>';
          }
        }
    	  #Als wel ingevoerd een waarde heeft/true is
    	  if(!$welingevoerd){
    	    #Als niksingevoerd TRUE is
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
          #Bericht als itembox vol is
          elseif($itemboxvol){
            echo '<div class="red">'.$txt['alert_not_enough_place'].'</div>';
          }
        }
  	  }
	  
	  $disabled['1'] = 'disabled';
      $disabled['2'] = 'disabled';
      $disabled['3'] = 'disabled';
	  
      #Ruimte vast stellen Per item. Vast stellen welke items nog te koop zijn.
      if(($gebruiker['itembox'] == 'Blue box') OR ($itemgegevens['naam'] == 'Blue box')){
        $disabled['3'] = '';
      }
      elseif(($gebruiker['itembox'] == 'Yellow box') OR ($itemgegevens['naam'] == 'Yellow box')){
        $disabled['2'] = '';
        $disabled['3'] = '';
      }
      elseif(($gebruiker['itembox'] == 'Bag') OR ($itemgegevens['naam'] == 'Bag')){
        $disabled['1'] = '';
        $disabled['2'] = '';
        $disabled['3'] = '';
      }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
      <?php #Form starten
  	  echo '<form method="POST" name="items">
	  		<tr>
				<td>';
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
        if($select['naam'] == "Pokedex zzchip"){
          $icon = 'gold';
          $prijs = number_format(round($select['gold']),0,",",".");
        }
        else{
          $icon = 'silver';
          $prijs = number_format(round($select['silver']),0,",",".");
        }

  	    #kijken als gebruiker het item al heeft of als het gekochte item gelijk als als de naam die er voor komt.
  	    if(($gebruiker[$select['naam']] == 1) || (($itemgegevens['naam'] == $select['naam']) AND ($welingevoerd))) #Als speler item al heeft deze disabelen
          $disabled[$j] = 'disabled';
        echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><img src="images/items/'.$select['naam'].'.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/'.$icon.'.png" alt="Silver" title="Silver" style="margin-bottom:-3px;">'.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="radio" name="productnaam" value="'.$select['naam'].'" '.$disabled[$j].'></center></div></td>
			</tr>
		  </table>
		  </div>';
      }
	  echo'</td></tr>';
	    ?>
        <tr>
          <td><div style="padding-left:10px;"><button type="submit" name="items" class="button"><?php echo $txt['button_items']; ?></button></div></td>
        </tr> 
      </form> 
       
	    <?
    #items sluiten
		break;
	  #Als er op special items geklikt word. Het volgende laten zien
		case "specialitems":
		  $sql      = mysql_query("SELECT `id`, `naam`, `silver`, `gold`, `omschrijving_".GLOBALDEF_LANGUAGE."` FROM `markt` WHERE `soort`='special items' OR `soort`='rare candy'");
  
  		#Als er op de knop gedrukt word
      if(isset($_POST['specialitems'])){
        $gebruiker_silver = $gebruiker['silver'];
		$gebruiker_gold = $gebruiker['gold'];
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++){
          #Item id opvragen
          $itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
          #Als er geen aantal is
          if(empty($aantal)) $niksingevoerd = True;
          elseif(!is_numeric ($aantal)) $niksingevoerd = True;
          #Als er wel een aantal is
          elseif(!empty($aantal)){
            #Item gegevens laden
            $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver`, `gold` FROM `markt` WHERE `id`='".$itemid."'"));
			#silver of gold berekenen voor de balls
			if($itemgegevens['naam'] == 'Rare candy') $goldd = $aantal*($itemgegevens['gold']/1);
            else $silverr = $aantal*($itemgegevens['silver']/1);
            #itemruimte over berekenen
            $ruimteover = $ruimte['max']-$gebruiker['items'];
            #Kijken als het silver er wel voor is           
            if(($gebruiker_silver < $silverr) OR ($gebruiker_gold < $goldd)){
              echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            }
			elseif($aantal < 0)
    			echo'<div class="red">'.$txt['alert_not_enough_money'].'</div>';
			elseif(!ctype_digit($aantal))
  				echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            #Kijken als speler nog wel ruimte heeft voor de potions
            elseif($ruimteover < $aantal){
              if($aantal > 1) $netheid = "&#39;s";
              echo '<div class="red">'.$txt['alert_itembox_full_1'].' '.$ruimteover.' '.$itemgegevens['naam'].''.$netheid.' '.$txt['alert_itembox_full_2'].'</div>';
            }
            else{
              #Opslaan
              $totalesilver += $silverr;
              $gebruiker_silver -= $silverr;
			  $totalegold += $goldd;
			  $gebruiker_gold -= $goldd;
              #mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$silverr."' WHERE `user_id`='".$_SESSION['id']."'");
              mysql_query("UPDATE `gebruikers_item` SET `".$itemgegevens['naam']."`=`".$itemgegevens['naam']."`+'".$aantal."' WHERE `user_id`='".$_SESSION['id']."'");
              echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' '.$aantal.'x gekocht.</div>';
            }
          $welingevoerd = True;
          }
    	  }
    	  #silver opslaan
    	  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$totalesilver."', `gold`=`gold`-'".$totalegold."' WHERE `user_id`='".$_SESSION['id']."'");
    	  #Als wel ingevoerd een waarde heeft/true is
    	  if(!$welingevoerd){
    	    #Als niksingevoerd TRUE is
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
        }
  	  }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
  
      <?php #Form starten
  	  echo '<form method="POST" name="specialitems">
	  		<tr>
				<td>';  	  
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
  	    if($select['naam'] == "Rare candy"){
			$icon = 'gold';
			$prijs = number_format(round($select['gold']),0,",",".");
		}
		else{
			$icon = 'silver';
    		$prijs = number_format(round($select['silver']),0,",",".");
		}
          echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="'.$j.'">
				<input type="hidden" name="id'.$j.'" value="'.$select['id'].'">
				<img src="images/items/'.$select['naam'].'.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/'.$icon.'.png" alt="'.$icon.'" title="'.$icon.'" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="text" maxlength="2" class="text_short" name="aantal'.$select['id'].'" placeholder="0" style="float:none;" /></center></div></td>
			</tr>
		  </table>
		  </div>';
        }
	  echo'</td></tr>';
	    ?>
         <tr>
           <td><div style="padding-left:10px;"><button type="submit" name="specialitems" class="button"><?php echo $txt['button_spc_items']; ?></button></div></td>
         </tr> 
      </form>
          
      <?
    #items sluiten
		break;
	  #Als er op special items geklikt word. Het volgende laten zien
		case "stones":
		  $sql     = mysql_query("SELECT `id`, `naam`, `silver`, `omschrijving_".GLOBALDEF_LANGUAGE."` FROM `markt` WHERE `soort`='stones'");
  
	    #Als er op de knop gedrukt word
      if(isset($_POST['stones'])){
        $gebruiker_silver = $gebruiker['silver'];
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++){
          #Item id opvragen
          $itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
          #Als er geen aantal is
          if(empty($aantal)) $niksingevoerd = False;
          elseif(!is_numeric ($aantal)) $niksingevoerd = False;
          #Als er wel een aantal is
          elseif(!empty($aantal)){
            #Item gegevens laden
            $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver` FROM `markt` WHERE `id`='".$itemid."'"));
            #silver berekenen voor de balls
            $silverr = $aantal*($itemgegevens['silver']/1);
            #itemruimte over berekenen
            $ruimteover = $ruimte['max']-$gebruiker['items'];
            #Kijken als het silver er wel voor is           
            if($gebruiker_silver < $silverr){
              echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            }
			elseif($aantal < 0)
    			echo'<div class="red">'.$txt['alert_not_enough_money'].'</div>';
			elseif(!ctype_digit($aantal))
  				echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            #Kijken als speler nog wel ruimte heeft voor de potions
            elseif($ruimteover < $aantal){
              if($aantal > 1) $netheid = "&#39;s";
              echo '<div class="red">'.$txt['alert_itembox_full_1'].' '.$ruimteover.' '.$itemgegevens['naam'].''.$netheid.' '.$txt['alert_itembox_full_2'].'</div>';
            }
            else{
              #Opslaan
              $totalesilver += $silverr;
              $gebruiker_silver -= $silverr;
              #mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$silverr."' WHERE `user_id`='".$_SESSION['id']."'");
              mysql_query("UPDATE `gebruikers_item` SET `".$itemgegevens['naam']."`=`".$itemgegevens['naam']."`+'".$aantal."' WHERE `user_id`='".$_SESSION['id']."'");
              echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' '.$aantal.'x gekocht.</div>';
            }
          $welingevoerd = True;
          }
    	  }
    	  #silver opslaan
    	  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$totalesilver."' WHERE `user_id`='".$_SESSION['id']."'");

    	  #Als wel ingevoerd een waarde heeft/true is
    	  if(!$welingevoerd){
    	    #Als niksingevoerd TRUE is
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
        }
  	  }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
      <?php #Form starten
  	  echo '<form method="POST" name="stones">
	  		<tr>
				<td>';
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
  	    $prijs = number_format(round($select['silver']),0,",",".");
          echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="'.$j.'">
				<input type="hidden" name="id'.$j.'" value="'.$select['id'].'">
				<img src="images/items/'.$select['naam'].'.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/silver.png" alt="Silver" title="Silver" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="text" maxlength="2" class="text_short" name="aantal'.$select['id'].'" placeholder="0" style="float:none;" /></center></div></td>
			</tr>
		  </table>
		  </div>';
      }
	  echo'</td></tr>';
      ?>
         <tr>
          <td><div style="padding-left:10px;"><button type="submit" name="stones" class="button"><?php echo $txt['button_stones']; ?></button></div></td>
         </tr>   
       </form>
          
	    <?   
    	#Special items sluiten  
		break;

		#Als er op potions geklikt word, het volgende laten zien
		case "attacks":
		
		  
          if(empty($_GET['subpage'])) $subpage = 1; 
          else $subpage = $_GET['subpage']; 
          
		  #Max aantal pokemon per pagina
          $max = 20; 
		  $aantal_attacks = 92;
          $aantal_paginas = ceil($aantal_attacks/$max); 
          if($aantal_paginas == 0) $aantal_paginas = 1;   
          $pagina = $subpage*$max-$max; 
		  
		 $sql     = mysql_query("SELECT markt.id, markt.naam, silver, omschrijving_".GLOBALDEF_LANGUAGE.", tmhm.type1 , tmhm.type2
								FROM `markt`
								INNER JOIN tmhm
								ON markt.id = tmhm.id
								WHERE `soort`='tm' 
								LIMIT ".$pagina.", ".$max."");
  	
  	  #Als er op de knop gedrukt word
      if(isset($_POST['tm'])){
        $gebruiker_silver = $gebruiker['silver'];
		
		$itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
		  
		  echo $aantal;
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++){
          #Item id opvragen
          $itemid = $_POST['id'.$i];
          #Aantal opvragen van het itemid
          $aantal = $_POST['aantal'.$itemid];
          #Als er geen aantal is
          if(empty($aantal)) $niksingevoerd = True;
          elseif(!is_numeric($aantal)) $niksingevoerd = True;
          #Als er wel een aantal is
          elseif(!empty($aantal)){
            $niksingevoerd = False;
            #Gegevens laden van de te kopen item
            $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver` FROM `markt` WHERE `id`='".$itemid."'"));
            #Prijs bereken voor het aantal potions.
            $silverr = $aantal*($itemgegevens['silver']/1);
            #itemruimte over berekenen
            $ruimteover = $ruimte['max']-$gebruiker['items'];
            #Kijken als er wel genoeg silver is.
            if($gebruiker_silver < $silverr){
              echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            }
			elseif($aantal < 0)
    			echo'<div class="red">'.$txt['alert_not_enough_money'].'</div>';
			elseif(!ctype_digit($aantal))
  				echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
            #Kijken als speler nog wel ruimte heeft voor de potions
            elseif($ruimteover < $aantal){
              if($aantal > 1) $netheid = "&#39;s";
              echo '<div class="red">'.$txt['alert_itembox_full_1'].' '.$ruimteover.' '.$itemgegevens['naam'].''.$netheid.' '.$txt['alert_itembox_full_2'].'</div>';
            }
            else{
              #Opslaan
              $totalesilver += $silverr;
              $gebruiker_silver -= $silverr;
			        mysql_query("UPDATE `gebruikers_tmhm` SET `".$itemgegevens['naam']."`=`".$itemgegevens['naam']."`+'".$aantal."' WHERE `user_id`='".$_SESSION['id']."'");
              echo '<div class="green">'.$txt['success_market'].' '.$itemgegevens['naam'].' '.$aantal.'x gekocht.</div>';
            }
            $welingevoerd = True;
          }
    	  }
    	  #silver opslaan
    	  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$totalesilver."' WHERE `user_id`='".$_SESSION['id']."'");
    	  #Als wel ingevoerd een waarde heeft/true is
    	  if(!$welingevoerd){
    	    #Als niksingevoerd TRUE is
      	  if($niksingevoerd){
            echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
          }
        }
      }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
  	  <?php #Form starten
  	  echo '<form method="POST" name="tm">
	  		<tr>
			  <td>';
  	  
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){

		if($select['type2'] == $select['type1'])$type = $select['type1'];
		else $type = $select['type1'].' en '.$select['type2'];
		
		$prijs = number_format(round($select['silver']),0,",",".");
          echo '<div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="'.$j.'">
				<input type="hidden" name="id'.$j.'" value="'.$select['id'].'">
				<img src="images/items/Attack_'.$select['type2'].'.png" alt="'.$select['type1'].' type attack" title="'.$select['type1'].' type attack" width=24 height=24 /></center></td>
				

				</tr>
			<tr>
				<td><span class="smalltext"><center>'.$select['naam'].'</center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/silver.png" alt="Silver" title="Silver" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'<br />'.$type.' '.$txt['market_attack_types'].'\', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td><div style="padding-top:5px;"><center><input type="text" maxlength="2" class="text_short" name="aantal'.$select['id'].'" placeholder="0" style="float:none;" /></center></div></td>
			</tr>
		  </table>
		  </div>';
      }
	  echo'</td></tr>';
	  ?>
      <tr>
        <td><div style="padding-left:10px;"><button type="submit" name="tm" class="button"><?php echo $txt['button_attacks']; ?></button></div></td>
      </tr>
    </form>  

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
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&shopitem='.$_GET['shopitem'].'&subpage='.$back.'"> &lt; </a>';
          }
          for($i = 1; $i <= $aantal_paginas; $i++) 
          { 
              
            if((2 >= $i) && ($subpage == $i)){
              echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
            }
            elseif((2 >= $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&shopitem='.$_GET['shopitem'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage == $i)){
              echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&shopitem='.$_GET['shopitem'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
            }
            else{
              $max = $subpage+3;
              $min = $subpage-3;  
              if($page == $i){
                echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
              }
              elseif(($min < $i) && ($max > $i)){
              	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&shopitem='.$_GET['shopitem'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
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
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&shopitem='.$_GET['shopitem'].'&subpage='.$next.'"> &gt; </a>';
          }
          echo "</div></center></td></tr>";
  	    
          ?>
    
      <?
    #Attack sluiten
    break;
	
	#Als er op pokemon geklikt word, het volgende laten zien
	case "pokemon":
	
    $sql  = mysql_query("SELECT markt.id, markt.pokemonid, markt.silver, markt.gold, markt.omschrijving_".GLOBALDEF_LANGUAGE.", pokemon_wild.zeldzaamheid
							FROM markt
							INNER JOIN pokemon_wild
							ON markt.pokemonid = pokemon_wild.wild_id
							WHERE markt.soort='pokemon'
							AND markt.beschikbaar = '1'
							AND pokemon_wild.wereld = '".$gebruiker['wereld']."'");
		  
    #Als er op de knop gedrukt word
    if(isset($_POST['pokemon'])){
      #Gegevens laden van het item
      $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `id`, `pokemonid`, `silver`, `gold`, `beschikbaar` FROM `markt` WHERE `id`='".$_POST['productid']."'"));
      #Als er niks aangvinkt is.
      if(empty($_POST['productid'])){
        echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
      }
      #Is het product nog in stock?
      elseif($itemgegevens['beschikbaar'] != 1){
        echo '<div class="red">'.$txt['alert_not_in_stock'].'</div>';
      }
      #Heeft speler niet genoeg silver?
      elseif(($gebruiker['silver'] < $itemgegevens['silver']) OR ($gebruiker['gold'] < $itemgegevens['gold'])){
        echo '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
      }
      #Heeft speler nog ruimte in hand?
      elseif($gebruiker['in_hand'] > 5){
        echo '<div class="red">'.$txt['alert_hand_full'].'</div>';
      }
      #Alles is goed
      else{
        #tijd van nu fixen
        $tijd = date('Y-m-d H:i:s');
        $opzak_nummer = $gebruiker['in_hand']+1;
        #Willekeurige pokemon laden, en daarvan de gegevens
        $query = mysql_fetch_assoc(mysql_query("SELECT wild_id, naam, groei, attack_base, defence_base, speed_base, `spc.attack_base`, `spc.defence_base`, hp_base, aanval_1, aanval_2, aanval_3, aanval_4 FROM pokemon_wild WHERE wild_id = '".$itemgegevens['pokemonid']."'"));
        
        #De willekeurige pokemon in de pokemon_speler tabel zetten
        mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) SELECT `wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4` FROM `pokemon_wild` WHERE `wild_id`='".$query['wild_id']."'");
        #id opvragen van de insert hierboven
        $pokeid	= mysql_insert_id();
        
        #Karakter kiezen 
        $karakter  = mysql_fetch_assoc(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
        
        #Expnodig opzoeken en opslaan
        $experience = mysql_fetch_assoc(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$query['groei']."' AND `level`='6'"));
        
        #Pokemon IV maken en opslaan
        #Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
        $attack_iv       = rand(2,31);
        $defence_iv      = rand(2,31);
        $speed_iv        = rand(2,31);
        $spcattack_iv    = rand(2,31);
        $spcdefence_iv   = rand(2,31);
        $hp_iv           = rand(2,31);
        
        #Stats berekenen
        $attackstat     = round((((($query['attack_base']*2+$attack_iv)*5/100)+5)*1)*$karakter['attack_add']);
        $defencestat    = round((((($query['defence_base']*2+$defence_iv)*5/100)+5)*1)*$karakter['defence_add']);
        $speedstat      = round((((($query['speed_base']*2+$speed_iv)*5/100)+5)*1)*$karakter['speed_add']);
        $spcattackstat  = round((((($query['spc.attack_base']*2+$spcattack_iv)*5/100)+5)*1)*$karakter['spc.attack_add']);
        
        
        $spcdefencestat = round((((($query['spc.defence_base']*2+$spcdefence_iv)*5/100)+5)*1)*$karakter['spc.defence_add']);
        $hpstat         = round(((($query['hp_base']*2+$hp_iv)*5/100)+5)+10);
        
        #Alle gegevens van de pokemon opslaan
        mysql_query("UPDATE `pokemon_speler` SET `level`='5', `karakter`='".$karakter['karakter_naam']."', `expnodig`='".$experience['punten']."', `user_id`='".$_SESSION['id']."', `opzak`='ja', `opzak_nummer`='".$opzak_nummer."', `ei`='1', `ei_tijd`='".$tijd."', `attack_iv`='".$attack_iv."', `defence_iv`='".$defence_iv."', `speed_iv`='".$speed_iv."', `spc.attack_iv`='".$spcattack_iv."', `spc.defence_iv`='".$spcdefence_iv."', `hp_iv`='".$hp_iv."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokeid."'");
        
        ##################EINDE POKEMON GEVEN
        
        mysql_query("UPDATE markt SET beschikbaar = '0' WHERE id = '".$itemgegevens['id']."'");
        mysql_query("UPDATE gebruikers SET silver = silver-'".$itemgegevens['silver']."', gold = gold-'".$itemgegevens['gold']."', aantalpokemon = aantalpokemon+'1' WHERE user_id = '".$_SESSION['id']."'");
        
        echo '<div class="green">'.$txt['success_market'].' '.$txt['success_bought_pokemon'].' gekocht.</div>';
      }
    }
?>
<table width="660" cellpadding="0" cellmargin="0">
  <tr>
    <td width="120" rowspan="54" valign="top"><img src="images/market.gif" /></td>
  </tr>
  	<?php if(mysql_num_rows($sql) == 0){
		  echo '<tr><td><center>'.$txt['out_of_stock_1'].' '.$gebruiker['wereld'].' '.$txt['out_of_stock_2'].'</div></tr></center>';
	  }
	  else{
		  
      #Form starten
  	  echo '<form method="post">
	  		<tr>
				<td>';
  	  for($j=1; $select = mysql_fetch_assoc($sql); $j++){
  		  if($select['gold'] == 0){
  			  $img = 'silver';
  			  $prijs = number_format(round($select['silver']),0,",",".");
  		  }
  		  if($select['silver'] == 0){
  			  $img = 'gold';
  			  $prijs = number_format(round($select['gold']),0,",",".");
  		  }
  		  if($select['zeldzaamheid'] == 1) $name = $txt['not_rare'];
  		  elseif($select['zeldzaamheid'] == 2) $name = $txt['middle_rare'];
  		  else $name = $txt['rare'];
  		
          echo '<div style="padding:10px; float:left;">
  		  <table width="80" class="greyborder">
  		  	<tr>
  				<td><center><img src="images/icons/egg2.gif" width=16 height=16 /></center></td>
  			</tr>
  			<tr>
  				<td><span class="smalltext"><center>'.$name.'</center></span></td>
  			</tr>
  			<tr>
  				<td><span class="smalltext"><center><img src="images/icons/'.$img.'.png" alt="'.$img.'" title="'.$img.'" style="margin-bottom:-3px;"> '.$prijs.'</center></span></td>
  			</tr>
  			<tr>
  				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$select['omschrijving_'.$_COOKIE['pa_language']].'\', this)">[?]</a></center></div></td>
  			</tr>
  			<tr>
  				<td><div style="padding-top:5px;"><center><input type="radio" name="productid" value="'.$select['id'].'"></center></div></td>
  			</tr>
  		  </table>
  		  </div>';
      }
	   echo'</td></tr>';
	    ?>
        <tr>
          <td><div style="padding-left:10px;"><button type="submit" name="pokemon" class="button"><?php echo $txt['button_pokemon']; ?></button></div></td>
        </tr> 
      </form> 
<?php } break;
	}
?>
</table>