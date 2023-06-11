<?
$page = 'work';

$select = mysql_fetch_assoc(mysql_query("SELECT land FROM gebruikers WHERE user_id = '".$_SESSION['id']."'"));
#Event taal pack includen
$eventlanguage = GetEventLanguage($select['land']);
include('language/events/language-events-nl.php');

#Elke werk soort is anders
if($gebruiker['soortwerk'] == 1){
  #Formule moeilijkheids graad
  $formule = 70;
  #silverbedrag kiezen
  $bedrag = rand(7,15);
  if($bedrag == 15) $berichtgelukt = array('1' => $txt['event_work_1_1']);
  elseif($bedrag != 15) $berichtgelukt = array('1' => $txt['event_work_1_2_1'].' <img src="images/icons/silver.png" width="16" height="16" alt="Silver" class="imglower"> '.$bedrag.' '.$txt['event_work_1_2_2']);
  $berichtmislukt = array('1' => $txt['event_work_1_3'],
                          '2' => $txt['event_work_1_4']);
}
elseif($gebruiker['soortwerk'] == 2){
  #Formule moeilijkheids graad
  $formule = 70;
  #silverbedrag kiezen
  $bedrag = 20; 
  $berichtgelukt = array('1' => $txt['event_work_2_1'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_2_2'],
                          '2' => $txt['event_work_2_3']);
  $berichtjail = array('1' => $txt['event_work_2_4']);
  #Jail tijd
  $tijd = rand(60,180);
}
elseif($gebruiker['soortwerk'] == 3){
  #Makkelijk
  $formule = 70;
  #silverbedrag kiezen
  $bedrag = rand(20,40); 
  if($bedrag == 40) $berichtgelukt = array('1' => $txt['event_work_3_1'].' '.$bedrag.'!');
  else $berichtgelukt = array('1' => $txt['event_work_3_2'].' '.$bedrag.'!');
  #Berichten opstellen en in array zetten
  $berichtmislukt = array('1' => $txt['event_work_3_3'],
                          '2' => $txt['event_work_3_4']);
}
elseif($gebruiker['soortwerk'] == 4){
  #Makkelijk
  $formule = 70;
  #silverbedrag kiezen
  $bedrag = 50; 
  $berichtgelukt = array('1' => $txt['event_work_4_1'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_4_2'],
                          '2' => $txt['event_work_4_3']);        
}
elseif($gebruiker['soortwerk'] == 5){
  #Middelmatig
  $formule = 70;
  #silverbedrag kiezen
  $bedrag = 60; 
  $berichtgelukt = array('1' => $txt['event_work_5_1'].' '.$bedrag.'!');
  #Berichten opstellen en in array zetten
  $berichtmislukt = array('1' => $txt['event_work_5_2'],
                          '2' => $txt['event_work_5_3']);
}
elseif($gebruiker['soortwerk'] == 6){
  #Middelmatig
  $formule = 40;
  #silverbedrag kiezen
  $bedrag = rand(50, 120); 
  if($bedrag == 120) $berichtgelukt = array('1' => $txt['event_work_6_1'].' '.$bedrag.'!');
  else $berichtgelukt = array('1' => $txt['event_work_6_2'].' '.$bedrag.'.');
  $berichtmislukt = array('1' => $txt['event_work_6_3'],
                          '2' => $txt['event_work_6_4']);
}
elseif($gebruiker['soortwerk'] == 7){
  #Middelmatig
  $formule = 35;
  #silverbedrag kiezen
  $bedrag = 150;  
  $berichtgelukt = array('1' => $txt['event_work_7_1'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_7_2'],
                          '2' => $txt['event_work_7_3']);
}
elseif($gebruiker['soortwerk'] == 8){
  #Middelmatig
  $formule = 30;
  #silver bedrag nemen
  $bedrag = 300; 
  $berichtgelukt = array('1' => $txt['event_work_8_1'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_8_2'],
                          '2' => $txt['event_work_8_3']);     
}
elseif($gebruiker['soortwerk'] == 9){
  #Moeilijk
  $formule = 25;
  #silver bedrag nemen
  $bedrag = rand(100,500); 
  if($bedrag == 500) $berichtgelukt = array('1' => $txt['event_work_9_1'].' '.$bedrag.'!');
  elseif($bedrag < 200) $berichtgelukt = array('1' => $txt['event_work_9_2'].' '.$bedrag.'.');
  elseif($bedrag > 200) $berichtgelukt = array('1' => $txt['event_work_9_3'].' '.$bedrag.'.');
  $berichtmislukt = array('1' => $txt['event_work_9_4'],
                          '2' => $txt['event_work_9_5']);
}
elseif($gebruiker['soortwerk'] == 10){
  #Moeilijk
  $formule = 10;
  #silver bedrag nemen
  $bedrag = 550; 
  $berichtgelukt = array('1' => $txt['event_work_10_1'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_10_2'],
                          '2' => $txt['event_work_10_3']); 
}
elseif($gebruiker['soortwerk'] == 11){
  #Moeilijk
  $formule = -10;
  #silver bedrag kiezen
  $bedrag = rand(600, 1000); 
  if($bedrag == 1000) $berichtgelukt = array('1' => $txt['event_work_11_1'].' '.highamount($bedrag).'!');
  else $berichtgelukt = array('1' => $txt['event_work_11_2'].' '.$bedrag.'!');
  $berichtmislukt = array('1' => $txt['event_work_11_3'],
                          '2' => $txt['event_work_11_4']);
  $berichtjail    = array('1' => $txt['event_work_11_3']);
  #Jail tijd
  $tijd = rand(180,300);
}
elseif($gebruiker['soortwerk'] == 12){
  #Heel moeilijk
  $formule = -25;
  #silver bedrag kiezen
  $bedrag = rand(1000, 2000); 
  if($bedrag == 2000) $berichtgelukt = array('1' => $txt['event_work_12_1'].' '.highamount($bedrag).'!');
  else $berichtgelukt = array('1' => $txt['event_work_12_2_1'].' '.highamount($bedrag).' '.$txt['event_work_12_2_2']);
  $berichtmislukt = array('1' => $txt['event_work_12_3'],
                          '2' => $txt['event_work_12_4']);
  $berichtjail    = array('1' => $txt['event_work_12_4']);
  #Jail tijd
  $tijd = rand(300,600);
}


#Kans berekenen
$kans = $formule+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / ($gebruiker['soortwerk']*3)));
#Als je geen kans hebt, toch kleine kans maken. En nooit 100%..
if($kans < 0) $kans = 3;
elseif($kans > 100) $kans = 95;
#fixen
$gelukt = kans($kans);

#Als het gelukt is.
if($gelukt == true){
  #Tell alle arrays
  $aantalgelukt = count($berichtgelukt);
  $berichtnummer = rand(1,$aantalgelukt);
  $bericht = $berichtgelukt[$berichtnummer];
  $color = 'green';
  #silver bedrag opslaan
  mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$bedrag."', `werkervaring`=`werkervaring`+'1', `soortwerk`='' WHERE `user_id`='".$_SESSION['id']."'");
  #Speler er EXP bij geven voor de Rank
  rankerbij('werken',$txt);
}
else{
  $color = 'red';
  $jail = rand(1,3);
  if((!empty($berichtjail[1])) && ($jail == 2)){
    $aantaljail = count($berichtjail);
    $berichtnummer = rand(1,$aantaljail);
    $bericht = $berichtjail[$berichtnummer];
    $tijdnu = date('Y-m-d H:i:s');
    $time = date("i:s", $tijd);
    $time = explode(":", $time);
    $bericht .= " ".$txt['event_jail_1']." ".$time[0]." ".$txt['event_jail_2']." ".$time[1]." ".$txt['event_jail_3']."</div>";
    mysql_query("UPDATE `gebruikers` SET `werkervaring`=`werkervaring`+'1', `soortwerk`='', `gevangenistijd`='".$tijd."', `gevangenistijdbegin`='".$tijdnu."' WHERE `user_id`='".$_SESSION['id']."'");
    #rank er af
    rankeraf('werken');  
  }
  else{
    #Tel alle arrays
    $aantalmislukt = count($berichtmislukt);
    $berichtnummer = rand(1,$aantalmislukt);
    $bericht = $berichtmislukt[$berichtnummer];
    mysql_query("UPDATE `gebruikers` SET `werkervaring`=`werkervaring`+'1', `soortwerk`='' WHERE `user_id`='".$_SESSION['id']."'");
  }
}

## EVENT
#Bericht opstellen na wat de language van de user is
$event = '<img src="images/icons/'.$color.'.png" width="16" height="16" class="imglower"> '.$bericht;

#Melding geven aan de uitdager
mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
VALUES (NULL, NOW(), '".$_SESSION['id']."', '".$event."', '0')");
?>