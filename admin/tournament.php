<script language="JavaScript" src="javascripts/calendar_db.js"></script>
<link rel="stylesheet" href="stylesheets/calendar.css">
<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 2) header('location: index.php?page=home');

if(isset($_POST['open'])){
  if(mysql_num_rows(mysql_query("SELECT tijd FROM toernooi WHERE deelnemers='0'")) > 0)
    echo 'There is another open tournament!';
  elseif(empty($_POST['speeltijd']))
    echo 'No time entered';
  elseif(empty($_POST['sluitdatum']))
    echo 'No time entered';
  else{
    $tijd = $_POST['speeltijd'].":00";
    mysql_query("INSERT INTO toernooi (tijd, sluit)
     VALUES('".$tijd."','".$_POST['sluitdatum']."')");
    echo 'Goedzo!';  
  }
}

?>
<form method="post" name='tour'>
  Open New Tournament.                         <br>
  Playtime: <input type="text" name="speeltijd"> example: 18:15<br />
                                    <br>
  Registration closing date and 2 hours before tournament..<input type="text" name="sluitdatum" class="text_long" value="<?php if($_POST['sluitdatum'] != '') echo $_POST['sluitdatum']; ?>" maxlength="10"/>
			<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'tour',
					// input name
					'controlname': 'sluitdatum'
				});
			</script><br><br>
  <input type="submit" name="open" value="Open Tournament">
</form>
<HR>
<?

if(isset($_POST['close'])){
  $rondes = $_POST['rounds'];
  $inserts = pow(2, $rondes);
  
  $tickets_sql = mysql_query("SELECT user_id FROM toernooi_inschrijving WHERE toernooi='".$_POST['tourid']."' ORDER BY id ASC LIMIT 0, ".$inserts."");
  $inserts /= 2;
  $ins_arr = Array();
  $ver_arr = Array();
  $new_ver_arr = Array();
  while($tickets = mysql_fetch_array($tickets_sql)){
    if($inserts > 0){
      $inserts--;
      mysql_query("INSERT INTO toernooi_ronde (toernooi, ronde, user_id_1, gereed) 
        VALUES ('".$_POST['tourid']."', '".$rondes."', '".$tickets['user_id']."', '2')");
      array_push($ins_arr, mysql_insert_id());
      array_push($ver_arr, mysql_insert_id());
    }
    else{
      shuffle($ins_arr);
      mysql_query("UPDATE toernooi_ronde SET user_id_2='".$tickets['user_id']."' WHERE id='".array_pop($ins_arr)."'");
    }
  }
    
  //Vervolg rondes invoeren
  while($rondes > 1){
    $rondes--;
    $inserts = (pow(2, $rondes))/2;
    while($inserts > 0){
      shuffle($ver_arr);
      mysql_query("INSERT INTO toernooi_ronde (toernooi, ronde, user_id_1, user_id_2) 
        VALUES ('".$_POST['tourid']."', '".$rondes."', '".array_pop($ver_arr)."', '".array_pop($ver_arr)."')");
      array_push($new_ver_arr, mysql_insert_id());
      $inserts--;
    }
    $ver_arr = $new_ver_arr;
    $new_ver_arr = Array();
  }

  mysql_query("UPDATE toernooi SET ronde='".$_POST['rounds']."', huidige_ronde='".$_POST['rounds']."', deelnemers='".pow(2, $_POST['rounds'])."' WHERE toernooi='".$_POST['tourid']."'");
  mysql_query("DELETE FROM toernooi_inschrijving WHERE toernooi='".$_POST['tourid']."'");
  echo 'Toernooi succesvol gestart.<br />';
}

$i = 0;
$new_tour_sql = mysql_query("SELECT toernooi, tijd FROM toernooi WHERE deelnemers = '0'");
if(mysql_num_rows($new_tour_sql) > 0){

  $new_tour = mysql_fetch_array($new_tour_sql);
  echo '<center>Inschrijf Schema!<br> Dit toernooi word gespeeld om '.$new_tour['tijd'].'</center>';
  echo '# DOETMEE Ingeschreven Leden:<br />';
  $deelnemers_sql = mysql_query("SELECT user_id FROM toernooi_inschrijving WHERE toernooi='".$new_tour['toernooi']."' ORDER BY id ASC");
  $spelers = mysql_num_rows($deelnemers_sql);
  $rondes = 0;  
  $places = 0; 
  do{
    $rondes++;
    $places = pow(2, $rondes);
  }while($places-$spelers <= 0);
  $rondes--;
     
  $places = pow(2, $rondes);
  if($places%2) $places = 0;
 
  while($deelnemer = mysql_fetch_array($deelnemers_sql)){
    if($speler >= $places) $doetmee = "nee";
    else $doetmee = "ja";
    $speler++;
    echo $speler."   ".$doetmee."   ".$deelnemer['user_id']."<br />";
  }

  echo '
    <form method="post">
      <input type="submit" name="close" value="Connect registration">
      <input type="hidden" name="rounds" value="'.$rondes.'">
      <input type="hidden" name="tourid" value="'.$new_tour['toernooi'].'">
    </form>
  ';
}