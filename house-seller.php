<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'house-seller';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

  #Naam van huis wat je nu hebt:
  if($gebruiker['huis'] == "doos") $huusnu = $txt['house1'];
  elseif($gebruiker['huis'] == "shuis") $huusnu = $txt['house2'];
  elseif($gebruiker['huis'] == "nhuis") $huusnu = $txt['house3'];
  elseif($gebruiker['huis'] == "villa") $huusnu = $txt['house4'];
   elseif($gebruiker['huis'] == "hotel") $huusnu = $txt['house5'];
	 
#Als er op de Buy knop gedrukt word
if(isset($_POST['koop'])){
  #Naamopbouwen
  if($_POST['huis'] == "doos") $huus = $txt['house1'];
  elseif($_POST['huis'] == "shuis") $huus = $txt['house2'];
  elseif($_POST['huis'] == "nhuis") $huus = $txt['house3'];
  elseif($_POST['huis'] == "villa") $huus = $txt['house4'];
  elseif($_POST['huis'] == "hotel") $huus = $txt['house5'];
  #Gegevens laden van het huis
  $gegevenhuis = mysql_fetch_assoc(mysql_query("SELECT `kosten` FROM `huizen` WHERE `afkorting`='".$_POST['huis']."'"));
  
  #Heeft de speler dit huis al?
  if(empty($_POST['huis'])) echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
  #Heeft de speler dit huis al?
  elseif($_POST['huis'] == $gebruiker['huis']) echo '<div class="red">'.$txt['alert_you_own_this_house'].'</div>';
  #heeft de speler wel genoeg silver?
  elseif($gebruiker['silver'] < $gegevenhuis['kosten']) echo '<div class="red">'.$txt['alert_not_enough_silver'].'</div>';
  #Heeft de speler al een villa?
  elseif($gebruiker['huis'] == "Villa") echo '<div class="red">'.$txt['alert_already_have_villa'].'</div>';
  #Heeft de speler een nhuis en wil hij/zij iets anders kopen dan een villa?
  elseif(($gebruiker['huis'] == "nhuis") AND ($_POST['huis'] != "villa")) echo '<div class="red">'.$txt['alert_you_have_better_now'].'</div>';
  #Heeft de speler een klein huis en wil hij/zij een doos kopen?
  elseif(($gebruiker['huis'] == "shuis") AND ($_POST['huis'] == "doos")) echo '<div class="red">'.$txt['alert_you_have_better_now'].'</div>';
  #Is alles goed dan dit uitvoeren
  else{
    #Er is een error en bericht opstellen
    echo '<div class="green">'.$txt['success_house_1'].' '.$huus.' '.$txt['success_house_2'].'</div>';
    #Opslaan
    mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$gegevenhuis['kosten']."', `huis`='".$_POST['huis']."' WHERE `user_id`='".$_SESSION['id']."'");
  }
}

$keet['1'] = 'disabled';
$keet['2'] = '';
$keet['3'] = '';
$keet['4'] = '';
$keet['5'] = '';
$button = '';

if($gebruiker['huis'] == "doos"){
}
elseif($gebruiker['huis'] == "shuis"){
  $keet['2'] = 'disabled';
}
elseif($gebruiker['huis'] == "nhuis"){
  $keet['2'] = 'disabled';
  $keet['3'] = 'disabled';
}
elseif($gebruiker['huis'] == "villa"){
  $keet['1'] = 'disabled';
  $keet['2'] = 'disabled';
  $keet['3'] = 'disabled';
  $keet['4'] = 'disabled';
  $button = 'disabled';
}
elseif($gebruiker['huis'] == "hotel"){
  $keet['1'] = 'disabled';
  $keet['2'] = 'disabled';
  $keet['3'] = 'disabled';
  $keet['4'] = 'disabled';
  $keet['5'] = 'disabled';
  $button = 'disabled';
}

$sql = mysql_query("SELECT * FROM `huizen`");
?>
<form method="post">
<center>
  <table width="660" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4"><center><?php echo $txt['title_text']; ?> <strong><?php echo $huusnu; ?></strong>.<br /><br /></center>
      </td>
    </tr>
      <tr>
        <td width="70" class="top_td"><center>#</center></td>
        <td width="140" class="top_td"><center><?php echo $txt['house']; ?></center></td>
        <td width="90" class="top_td"><?php echo $txt['price']; ?></td>
        <td width="360" class="top_td"><?php echo $txt['description']; ?></td>
      </tr>
      <?php
      for($j=1; $select = mysql_fetch_assoc($sql); $j++){
        $prijs = number_format(round($select['kosten']),0,",",".");
        echo '
          <tr>
            <td class="normal_td"><center><input type="radio" name="huis" value="'.$select['afkorting'].'" '.$keet[$j].'/></center></td>
            <td class="normal_td" height="80"><center><img src="'.$select['link'].'" alt="'.$select['naam_'.GLOBALDEF_LANGUAGE.''].'"/></center></td>
            <td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$prijs.'</td>
            <td class="normal_td">'.$select['omschrijving_'.GLOBALDEF_LANGUAGE.''].'</td>
          </tr>';
      }
      ?>
      <tr>
        <td colspan="4"><button type="submit" name="koop" class="button"><?php echo $txt['button']; ?></button></td>
      </tr>
  </table>
</center>
</form>