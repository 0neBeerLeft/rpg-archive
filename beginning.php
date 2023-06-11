<? 
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'beginning';
#Goeie taal erbij laden voor de page
include('language/language-pages.php');

#Heeft de speler nog geen ei, dan pagina zien
if($gebruiker['eigekregen'] == 0){
  #Als er op de knop gedrukt is de keuze includen
  if((isset($_POST['verder'])) OR ($_SESSION['eikeuze'] == 1)){
    $_SESSION['eikeuze'] = 1;
    include('choose-pokemon.php');
  }
  #Anders de pagina laten zien
  else{
  ?>
<center>
        <table width="600" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="120" valign="top"><img src="images/oak.png" /></td>
            <td width="380" valign="top"><p><?php echo $txt['title_text']; ?></p>
            <form method="post" action="index.php?page=choose-pokemon"><button type='submit' name='verder' class='button' ><?php echo $txt['button']; ?></button></form></td>
          </tr>
        </table></center>
  <?
  }#Pagina wel of niet laten zien
}
#Speler heeft al ei gehad. Terug naar home
else{
  header("Location: index.php?page=home");
}
?>