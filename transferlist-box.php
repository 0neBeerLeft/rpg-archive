<?
#Sessies aan zetten
session_start(); 

$page = 'transferlist-box';
#Goeie taal erbij laden voor de box
include('language/language-box.php');

#config laden
include_once('includes/config.php');
include_once('includes/ingame.inc.php');
include_once('includes/globaldefs.php');

#Gegevens ophalen en decoderen
$tid = base64_decode($_GET['tid']);
#Gegevens van transferlijst halen
#Gegevens van de te kopen pokemon laden
$select = mysql_fetch_assoc(mysql_query("SELECT t.id, t.silver,t.gold, ps.wild_id, ps.id, ps.user_id, ps.roepnaam, ps.shiny, ps.level, pw.zeldzaamheid, pw.naam, g.user_id, g.username, g.land, g.missie_4
										FROM pokemon_speler AS ps 
										INNER JOIN pokemon_wild AS pw
										ON ps.wild_id = pw.wild_id
										INNER JOIN gebruikers AS g 
										ON ps.user_id = g.user_id 
										INNER JOIN transferlijst AS t 
										ON ps.id = t.pokemon_id 
										WHERE t.id='".$tid."'"));
#Gegevens van jou laden
$you = mysql_fetch_assoc(mysql_query("SELECT username, silver,gold, rank, huis FROM gebruikers WHERE user_id = '".$_SESSION['id']."'"));

#Je moet rank 3 zijn om deze pagina te kunnen zien
if($gebruiker['rank'] < 3) header("Location: index.php?page=home");

#Pokemon tellen die speler in "huis" heeft
$inhuis = mysql_num_rows(mysql_query("SELECT `id` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND (opzak = 'nee' OR opzak = 'tra')"));
              
if($you['huis'] == "doos") $over  = 2-$inhuis;
elseif($you['huis'] == "shuis") $over  = 20-$inhuis;
elseif($you['huis'] == "nhuis") $over  = 100-$inhuis;
elseif($you['huis'] == "villa") $over  = 650-$inhuis;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=GLOBALDEF_SITETITLE?></title>
</head>

<body>
<?
#Hoeveel is de pokemon waard:
$max_min = max_min_price($select);
#Naam veranderen als het male of female is.
$select['naam_goed'] = pokemon_naam($select['naam'],$select['roepnaam']);
#Max level bereken dat een speler kan kopen
$maxlevel = $you['rank']*5;

#bestaat het id wel?
if(empty($select['id']))
  echo '<div class="blue">'.$select['naam_goed'].' '.$txt['alert_sold'].'</div>';

else{
  #is het level van de te kopen pokemon te groot?
  if($maxlevel < $select['level'])
    echo '<div class="blue">'.$select['naam_goed'].' '.$txt['alert_too_low_rank_1'].' '. $maxlevel.' '.$txt['alert_too_low_rank_2'].'</div>';
  elseif($over < 1)
    echo '<div class="blue">'.$txt['alert_house_full'].'</div>';
  elseif($select['silver'] > $you['silver']) 
  	echo '<div class="red">'.$txt['alert_too_less_silver'].'</div>';
  else{
    #Als er op de koop knop gedrukt word
    if(isset($_POST['koop'])){
      #Heeft de koper wel genoeg silver
      $gekocht = False;
      if($select['silver'] > $you['silver'])
        echo '<div class="red">'.$txt['alert_too_less_silver'].'</div>';
      elseif($select['gold'] > $you['gold'])
        echo '<div class="red">'.$txt['alert_too_less_gold'].'</div>';
      elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `pokemon_id`='".$select['id']."'")) != 1)
        echo '<div class="blue">'.$select['naam_goed'].' '.$txt['alert_sold'].'</div>';
      else{
    	  $gekocht = True;
        if($select['gold'] == ''){
          $select['gold'] = 0;
        }
        #Pokémon to new owner. pokemon save as his transfer list
        mysql_query("UPDATE `pokemon_speler` SET `user_id`='".$_SESSION['id']."', `trade`='1.5', `opzak`='nee' WHERE `id`='".$select['id']."'");
        #Nieuwe eigenaar silver minderen
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$select['silver']."',`gold`=`gold`-'".$select['gold']."', `aantalpokemon`=`aantalpokemon`+'1' WHERE `user_id`='".$_SESSION['id']."'");
        #Oude eigenaar silver optellen
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$select['silver']."',`gold`=`gold`+'".$select['gold']."', `aantalpokemon`=`aantalpokemon`-'1' WHERE `user_id`='".$select['user_id']."'");
        #Verwijderen uit transferlijst tabel
        mysql_query("DELETE FROM `transferlijst` WHERE `pokemon_id`='".$select['id']."'");
        #Opslaan als gezien
        update_pokedex($select['wild_id'],'','buy');
        #Opslaan als gehad bij de ander
        if(mysql_num_rows(mysql_query("SELECT ps.id FROM pokemon_speler AS ps INNER JOIN gebruikers AS g ON ps.user_id = g.user_id WHERE g.username='".$select['username']."' AND ps.wild_id='".$select['wild_id']."'")) == 1)
          mysql_query("UPDATE gebruikers SET `pok_gehad`=concat(pok_gehad,',".$select['wild_id']."') WHERE username='".$select['username']."'");
        //complete mission 4
        if($select['missie_4'] == 0){
          mysql_query("UPDATE `gebruikers` SET `missie_4`=1, `silver`=`silver`+1250,`rankexp`=rankexp+50 WHERE `user_id`='".$select['user_id']."'");
          echo showToastr("info", "Je hebt een missie behaald!");
        }

		#Event taal pack includen
		$eventlanguage = GetEventLanguage($select['land']);
		include('language/events/language-events-'.$eventlanguage.'.php');

		$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> <a href="?page=profile&player='.$you['username'].'">'.$you['username'].'</a> '.$txt['event_bought_your'].' '.$select['naam'].' '.$txt['event_for'].' <img src="images/icons/silver.png" title="Silver" width="16" height="16" class="imglower" /> '.highamount($select['silver']).' en <img src="images/icons/gold.png" title="Gold" width="16" height="16" class="imglower" /> '.highamount($select['gold']).' '.$txt['event_silver_from_tf'];
		
		#Melding geven aan de uitdager
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
		VALUES (NULL, NOW(), '".$select['user_id']."', '".$event."', '0')");

      }
    }
    
	  #Link maken voor het plaatje van de pokemon
    $select['naam_klein'] = strtolower($select['naam']);
    if($select['shiny'] == 1) $shiny = 'shiny';
    else $shiny = 'pokemon';
    ?>
    <form method="post">
    <?php
    #Als speler de pokemon heeft gekocht
    if($gekocht){
    	echo 
        $txt['trbox_title_text_bought_1'].'<img src="images/'.$shiny.'/'.$select['wild_id'].'.gif" style="position: relative; float: right; padding-right: 6px;" /><br /><br />
      	'.$txt['trbox_title_text_bought_2'].' <b>'.$select['naam_goed'].'</b> '.$txt['trbox_title_text_bought_3'].'</b><br>
      	<b>'.$selectnaam.'</b> '.$txt['title_text_bought_4'];
    }
    else{
    	echo
        $txt['trbox_title_text_1'].' <strong>'.$select['naam_goed'].'</strong> '.$txt['trbox_title_text_2'].' <strong>'.$select['username'].'</strong>! <img src="images/'.$shiny.'/'.$select['wild_id'].'.png" style="position: relative; float: right; padding-right: 6px;" /><br /><br />
        '.$select['naam'].' '.$txt['trbox_title_text_3'].' <img src="images/icons/silver.png" title="Silver" /> <strong>'.$max_min['waard_mooi'].'</strong> '.$txt['trbox_title_text_4'].'<br /><br />
        '.$select['naam'].' '.$txt['trbox_title_text_5'].' <strong><img src="images/icons/silver.png" title="Silver" /> '.highamount($select['silver']).'</strong>

        '.$txt['trbox_title_text_8'].' <strong><img src="images/icons/gold.png" title="gold" /> '.highamount($select['gold']).'</strong>.<br /><br />

        '.$txt['trbox_title_text_6'].' <strong>'.$select['naam'].'</strong> '.$txt['trbox_title_text_7'].' '.$txt['trbox_title_text_9'].'<br /><br />
        <input type="submit" value="'.$txt['button_transferlist_box'].' '.$select['naam_goed'].'" name="koop" class="button" />';
    }
  }
}
?>
</form>
</body>
</html>
