<?
if(isset($_POST['koopuit'])){
  $jail_tijd_over = strtotime($gebruiker['gevangenistijdbegin'])+$gebruiker['gevangenistijd'];
  $jail_tijd_nu   = strtotime(date('Y-m-d H:i:s'));
  $jail_tijd      = $jail_tijd_over-$jail_tijd_nu;
  $jail_kosten    = $jail_tijd*4;
  $jail_kosten_mooi = number_format(round($jail_kosten),0,",",".");

  if($gebruiker['gevangenistijd'] == 0)
    echo '<div class="blue"><img src="images/icons/blue.png"> You hare already broken free.</div>';
  elseif($jail_tijd < 0)
    echo '<div class="blue"><img src="images/icons/blue.png"> You are quite.</div>';
  elseif($jail_kosten < 0)
    echo '<div class="blue"><img src="images/icons/blue.png"> You are quite.</div>';
  elseif($gebruiker['silver'] < $jail_kosten)
    echo '<div class="red"><img src="images/icons/red.png"> You do not have enough silver to yourself to buy</div>';
  else{
    mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$jail_kosten."', `gevangenistijd`='0' WHERE `user_id`='".$_SESSION['id']."'");
    echo '<div class="green"><img src="images/icons/green.png"> Je hebt jezelf uitgekocht voor <img src="images/icons/silver.png" title="Silver"> '.$jail_kosten_mooi.'</div>';
  }
}

else{
  echo "<center>Je zit nog <strong><span id=uur3></span></strong> <strong><span id=minuten3> </span>&nbsp;minuten</strong> en <strong><span id=seconden3></span>&nbsp;seconden</strong> in de gevangenis.\n";
  ?>
  <table width="350" border="0">
    <tr>
      <td><img src="images/jail.gif" style="border: 1px solid #000;" /></td>
    </tr>
  </table>
  
  <form method="post">
   <table width="350" border="0">
     <tr>
       <td width="175"><center>Buyout price: <strong><img src="images/icons/silver.png" title="Silver" /> <span id="bedrag"></span></strong></center></td>
       <td width="175"><center><input type="submit" name="koopuit" value="Buy" class="button_mini" /></center></td>
     </tr>
   </table> 
  </form>
  </center>
  
  <script type="text/javascript">
  var int3 = <? echo $jail_tijd; ?>;
  function aftellen3() {
    var inter3 = int3
    var kosten = inter3 * 4
    var uren3 = inter3 / 3600
    var uur3 = Math.floor(uren3)
    var gehad3 = uur3 * 3600
    var moetnog3 = inter3 - gehad3
    var minuten3 = moetnog3 / 60
    var mins3 = Math.floor(minuten3)
    var gehadmin3 = mins3 * 60
    var moetnogg3 = moetnog3 - gehadmin3
    var secs3 = moetnogg3
  
    if(inter3 <= 0) {
      clearInterval(interval3)
      document.location.reload()
    } 
    else {
      int3 = inter3 - 1
      //       document.getElementById('uur').innerHTML = uur
      document.getElementById('minuten3').innerHTML = mins3
      document.getElementById('seconden3').innerHTML = secs3
      document.getElementById('bedrag').innerHTML = kosten
    }  	
  }
  
  // functie 1e keer uitvoeren 
  aftellen3()
  // functie elke 1000 microsec. uitv. 
  interval3 = setInterval('aftellen3();', 1000)
  </script>
  <?
}
?>