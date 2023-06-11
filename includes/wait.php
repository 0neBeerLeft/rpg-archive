<?
if($type_timer == 'work'){
  $wait_tekst[0] = 'Je bent nog ';
  $wait_tekst[1] = ' aan het werk.';
}
elseif($type_timer == 'pokecenter'){
  $wait_tekst[0] = 'Het duurt nog ';
  $wait_tekst[1] = 'voordat je Pokemons zijn hersteld.';
}
?>
<center>
  <script type="text/javascript">
  var int3 = <? echo $wait_time ?>;  
	function aftellen3() {  

    var inter3 = int3;
    var minuten3 = inter3 / 60;
    var mins3 = Math.floor(minuten3);
    var gehadmin3 = mins3 * 60;
    var moetnogg3 = inter3 - gehadmin3;
    var secs3 = moetnogg3;
    
    if(inter3 < 0) {  	
      clearInterval(interval3);
      document.location.reload();
    } 
    else {
      int3 = inter3 - 1;
      document.getElementById('minuten3').innerHTML = mins3;
      document.getElementById('seconden3').innerHTML = secs3;
    }
  }
  </script>

  <div class="blue">
    <img src="images/icons/blue.png" width=16 height=16 /> <? echo $wait_tekst[0]; ?><strong>
    <span id=minuten3> </span>&nbsp;minuten</strong> en <strong>
    <span id=seconden3></span>&nbsp;seconden</strong><? echo $wait_tekst[1]; ?>
  </div>
  
  <script type=text/javascript>
    // functie 1e keer uitvoeren 
    aftellen3();  	
    // functie elke 1000 microsec. uitv. 
    interval3 = setInterval('aftellen3();', 1000);
  </script>
</center>