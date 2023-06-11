<?php
	#Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");

	$page = 'work';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
      #Als er op de werken knop gedrukt word dit inwerking stellen
      if(isset($_POST['werken'])){
        #Kijken als er wel een werk geselecteerd is
    		if($_POST['werk'] != 1 && $_POST['werk'] != 2 && $_POST['werk'] != 3 && $_POST['werk'] != 4 && $_POST['werk'] != 5 && $_POST['werk'] != 6 && $_POST['werk'] != 7 && $_POST['werk'] != 8 && $_POST['werk'] != 9 && $_POST['werk'] != 10 && $_POST['werk'] != 11 && $_POST['werk'] != 12)
    			echo '<div class="red">'.$txt['alert_nothing_selected'].'</div>';
        else{
          #Werk tijden opstellen
          $tijd = date('Y-m-d H:i:s');
          #Verkoop ranja op het plein
          if($_POST['werk'] == 1) $sec = 30;
          #In de Markt helpen
          elseif($_POST['werk'] == 2) $sec = 60;
          #De poke magazine rondbrengen
          elseif($_POST['werk'] == 3) $sec = 90;
          #Pokemon Center schoonmaken
          elseif($_POST['werk'] == 4) $sec = 120;
          #Potje golf tegen Team Rocket
          elseif($_POST['werk'] == 5) $sec = 120;
          #Zoek waardevolle spullen in de stad
          elseif($_POST['werk'] == 6) $sec = 150;
          #Hou een pokemon demonstratie op het plein
          elseif($_POST['werk'] == 7) $sec = 180;
          #freestyle in het park
          elseif($_POST['werk'] == 8) $sec = 210;
          #Bied je Pokemon aan voor een medisch experiment
          elseif($_POST['werk'] == 9) $sec = 210;	
          #Help agent Jenny
          elseif($_POST['werk'] == 10) $sec = 240;
          #Laat je pokemon stelen
          elseif($_POST['werk'] == 11) $sec = 270;      
          #Beroof een casino met je Pokemon
          elseif($_POST['werk'] == 12) $sec = 300;
		  
          #Tijden opslaan
          mysql_query("UPDATE `gebruikers` SET `werktijdbegin`='".$tijd."', `werktijd`='".$sec."', `soortwerk`='".$_POST['werk']."' WHERE `user_id`='".$_SESSION['id']."'");
          if($sec >= 60){
            $minuten = floor($sec/60);
            $seconden = $sec-($minuten*60);
            if($seconden == 0) $seconden = "";
            elseif($seconden < 10) $seconden = " ".$txt['and']." 0".$seconden." ".$txt['seconds']." ";
            else  $seconden = $seconden." ".$txt['seconds'];
            $werktijd = $minuten." ".$txt['minutes']." ".$seconden;
          }
          else{
            $werktijd = $sec." ".$txt['seconds']." ";
          }
          
          echo '<div class="green">'.$txt['success_work_1'].' '.$werktijd.' '.$txt['success_work_2'].'</div>';
          $knop = "disabled='disabled'";
          unset($_POST);
        }
      }
      ?> 
<center>
	<form method="post">           
		<table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="1" value="1" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_1']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="2" value="2" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_2']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 70+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 3));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 70+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 6));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 20</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>30 <?php echo $txt['seconds']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>60 <?php echo $txt['seconds']; ?></b></td>
		  </tr>
		</tbody>
		  </table>         
		<table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="3" value="3" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_3']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="4" value="4" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_4']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 70+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 9));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 70+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 12));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 50</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>1,5 <?php echo $txt['minutes']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>2 <?php echo $txt['minutes']; ?></b></td>
		  </tr>
		</tbody>
		  </table>
		  <table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="6" value="6" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_6']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="7" value="7" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_7']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 40+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 18));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 35+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 21));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 150</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>2,5 <?php echo $txt['minutes']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>3 <?php echo $txt['minutes']; ?></b></td>
		  </tr>
		</tbody>
		  </table>
		<table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="8" value="8" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_8']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="10" value="10" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_10']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 30+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 24));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 10+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 30));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 300</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 550</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>3,5 <?php echo $txt['minutes']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>4 <?php echo $txt['minutes']; ?></b></td>
		  </tr>
		</tbody>
		  </table>
		<table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="11" value="11" type="radio"></td>
		    <td colspan="4" width="460px;" style="padding-left:10px;"><b><?php echo $txt['work_11']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="12" value="12" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_12']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = -10+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 33));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = -25+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 36));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>4,5 <?php echo $txt['minutes']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>5 <?php echo $txt['minutes']; ?></b></td>
		  </tr>
		</tbody>
		  </table>
		  <?php if($gebruiker['premiumaccount'] > 0 || $gebruiker['admin'] < 1){ ?>
		<table class="general">
		  <tbody><tr>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="5" value="5" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_5']; ?></b></td>
		    <td rowspan="4" align="center" width="20px"><input name="werk" id="9" value="9" type="radio"></td>
		    <td colspan="4" width="460px;"><b><?php echo $txt['work_9']; ?></b></td>
		  </tr>
		  <tr>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/Ash-img.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 70+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 15));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		    <td rowspan="3" align="center" width="100px"><img src="/images/layout/ingame/prof-oak.png" style="border: 3px solid #000; border-radius: 3px;" alt="#"></td>
		    <td align="center" width="18px" style="padding-left:10px;"><img src="/images/icons/question.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2" width="340px"><?php echo $txt['chance']; ?>: <b><?php $kans = 25+(($gebruiker['rank']*5)+($gebruiker['werkervaring'] / 27));
									if($kans > 95) $kans = 95;
									elseif($kans < 0) $kans = 3;
							  	 	echo round($kans);
							  ?>%</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 60</b></td>
		    <td align="center" width="18px"><img src="/images/icons/silver.png" alt="Silver" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['turnover']; ?>: <b><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ??</b></td>
		  </tr>
		  <tr>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>2 <?php echo $txt['minutes']; ?></b></td>
		    <td align="center" width="18px"><img src="/images/icons/clock.png" alt="Wachttijd" class="imglower" height="16" width="16"></td>
		    <td colspan="2"><?php echo $txt['duration']; ?>: <b>3,5 <?php echo $txt['minutes']; ?></b></td>
		  </tr>
		</tbody>
		  </table>
		  <?php } ?>
      
      <table class="general">
		  <tbody><tr>
		    <td colspan="28" align="center"><center><button name="werken" class="button" type="submit"><? echo $txt['button']; ?></button></center></td>
		  </tr>
		
		</tbody>
  </table>
 </form>
</center>