<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 3) header('location: index.php?page=home');

?>
<form method="post">
<table width="150" border="0">
	<tr>
    	<td>Iedereen</td>
        <td><input type="radio" name="ontvanger" value="allemaal" onChange=this.form.submit();></td>
    </tr>
</table>	
</form>
<?

//Als er op de verstuur knop gedrukt word
if(isset($_POST['verstuur'])){
  //Kijken aan wie het gericht is.
  if($_POST['ontvanger'] == "persoon"){
    //Makkelijk naam toewijzen
    $bericht   = $_POST['tekst'];
    $ontvanger = $_POST['speler'];
    $onderwerp = $_POST['onderwerp'];
    //Als er geen bericht is ingetypt
    if(empty($bericht)) {
      echo '<div class="red"><img src="images/icons/red.png"> No text entered.</div>';
    }
    //Als er geen ontvanger is ingevuld
    elseif(empty($ontvanger)){
      echo '<div class="red"><img src="images/icons/red.png"> No receiver entered.</div>';
    }
    elseif(!preg_match('/[A-Za-z0-9_]+$/',$onderwerp)) {
      echo '<div class="red"><img src="images/icons/red.png"> The topic may not contain those characters.</div>';
    }
    //Als alles is ingevuld het bericht versturen
    else{
      //Als er geen onderwerp is ingevuld een onderwerp toewijzen
      if(empty($onderwerp)){
        $onderwerp =  "(Geen)";
      } 
      //In de database zetten
      //Tijd opvragen.
      $datum      = date('Y-m-d H:i:s');
      $verstuurd  = date('d-m-y H:i');
      //Spaties weghalen
      mysql_query("INSERT INTO `berichten` (`datum`, `ontvanger_id`, `afzender_id`, `bericht`, `onderwerp`, `gelezen`) 
        VALUES ('".$datum."', '".$ontvanger."', '1', '".$bericht."', '".$onderwerp."', '".$verstuurd."', 'nee')");
      echo '<div class="green"><img src="images/icons/green.png"> Message was sent successfully!</div>';
    }      
  }
  else{
    //Makkelijk naam toewijzen
    $bericht   = $_POST['tekst'];
    $onderwerp = $_POST['onderwerp'];
    //Als er geen bericht is ingetypt
    if(empty($bericht)) {
      echo '<div class="red"><img src="images/icons/red.png"> Geen tekst ingevuld.</div>';
    }
    elseif(!preg_match('/[A-Za-z0-9_]+$/',$onderwerp)) {
      echo '<div class="red"><img src="images/icons/red.png"> Het onderwerp mag geen tekens bevatten.</div>';
    }
    //Als alles is ingevuld het bericht versturen
    else{
      $speler = mysql_query("SELECT `user_id` FROM `gebruikers`");
      while($spelers = mysql_fetch_array($speler)){
        //Als er geen onderwerp is ingevuld een onderwerp toewijzen
        if(empty($onderwerp)){
          $onderwerp =  "(Geen)";
        } 
        //In de database zetten
        //Tijd opvragen.
        $datum      = date('Y-m-d H:i:s');
        mysql_query("INSERT INTO `berichten` (`datum`, `ontvanger_id`, `afzender_id`, `bericht`, `onderwerp`, `gelezen`) 
          VALUES ('".$datum."', '".$spelers['user_id']."', '1', '".$bericht."', '".$onderwerp."', 'nee')");
      }
    echo '<div class="green"><img src="images/icons/green.png"> Massabericht succesvol verstuurd.</div>';  
    }
  }

}

//Als er iets gekozen is
if(isset($_POST['ontvanger'])){
  echo '<form method="post">
  			<table width="600" border="0">';
    if($_POST['ontvanger'] == "persoon"){
      echo '<tr>
	  			<td>Ontvanger:</td>
				<td><input type="text" name="speler" class="text_long" value="'.$_POST['speler'].'"></td>
			</tr>';
    }
      echo '<tr>
				<td width="110">Topic:</td>
				<td width="490"><input type="text" name="onderwerp" class="text_long" value="Daily Reset"></td>
			</tr>
    		<tr>
				<td colspan="2"><textarea style="width:580px;" class="text_area" rows="15"  name="tekst">'.$_POST['tekst'].'</textarea></td>
			</tr>
			<tr>
				<td colspan="2"><input type="hidden" value="'.$_POST['ontvanger'].'" name="ontvanger">
					<input type="submit" value="Verstuur" name="verstuur" class="button"></td>
			</tr>
		</table>
  		</form>';
}
?>