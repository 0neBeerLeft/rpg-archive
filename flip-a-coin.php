<? 
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'flip-a-coin';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Word er op de doen knop gedrukt?
if(!empty($_POST['bedrag'])){
  #is er wel een bedrag ingevoerd?
  if(!preg_match('/[A-Za-z_]+$/',$_POST['bedrag'])){
    #Random getal nemen 
    $getal = rand(1,99);
    #eventuele punt vervangen in komma
    $bedrag = highamount($_POST['bedrag']);
    
    if($bedrag > $gebruiker['silver'])
      $bericht = '<div class="red">'.$txt['alert_too_less_silver'].'</div>';
    elseif($bedrag < 1) #Kijken als het ingevoerde bedrag wel meer dan 0 is
      $bericht = '<div class="red">'.$txt['alert_amount_unknown'].'</div>';
    elseif(!is_numeric($bedrag)) #is het getal wel numeriek?
      $bericht = '<div class="red">'.$txt['alert_amount_unknown'].'</div>';
    elseif($getal < 30){  #Is het getal oneven
      $bericht = '<div class="green">'.$txt['success_win'].' <img src="images/icons/silver.png" title="Silver" /> '.$bedrag.' silver!</div>';
      mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'".$_POST['bedrag']."' WHERE `user_id`='".$_SESSION['id']."'");
    }
    else{ #Is het getal even
      $bericht = '<div class="red">'.$txt['success_lose'].' <img src="images/icons/silver.png" title="Silver" /> '.$bedrag.' silver!</div>';
      mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$_POST['bedrag']."' WHERE `user_id`='".$_SESSION['id']."'");
    }
  }
  else #Is er geen bedrag ingevoerd
    $bericht = '<div class="red">'.$txt['alert_no_amount'].'</div>';
}
?>
<script language="JavaScript" type="text/javascript" src="javascripts/numeriek.js"></script>
<? if($bericht) echo $bericht; ?>
<center>
  <table width="56%" border="0">
  <tr>
    <td><center><p><?php echo $txt['title_text']; ?></p></center>
    </td>
  </tr>
  <tr>
    <td><center>
      <table width="230" border="0">
        <form method="post" action="?page=flip-a-coin">
          <tr>                
            <td width="33"><img src="images/icons/silver.png" title="Silver" /> </td>
            <td width="144"><input type="text" class="text_long" value="10" name="bedrag" onKeyPress="onlyNumeric(arguments[0])"></td>
            <td width="45"><button type="submit" name="doen" class="button"><?php echo $txt['button']; ?></button></td>
          </tr>
        </form>
      </table></center>
    </td>
    </tr>
  </table> 
</center>