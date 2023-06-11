<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'promotion';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
	$trainer = 1;
	if(isset($_POST['submit'])){
	if($trainer == 1){
        $query = mysql_fetch_assoc(mysql_query("SELECT `naam` FROM `trainer` WHERE `badge`='' AND (`gebied`='".$gebied."' OR `gebied`='All') ORDER BY rand() limit 1"));
        include('attack/trainer/trainer-start.php');
        mysql_data_seek($pokemon_sql, 0);
        $opzak = mysql_num_rows($pokemon_sql);
        $level = 0;
        while($pokemon = mysql_fetch_assoc($pokemon_sql)) $level += $pokemon['level'];
        $trainer_ave_level = $level/$opzak;
        //Make Fight
        $info = create_new_trainer_attack($query['naam'],$trainer_ave_level,$gebied);
        if(empty($info['bericht'])) header("Location: ?page=attack/trainer/trainer-attack");
        else echo "<div class='red'>".$txt['alert_no_pokemon']."</div>";
      }
	  }
?>
<form method='post'>

<center>
<img src="/images/homelogo.png" alt="Trainers" title="Trainers" /><br /><br />
Nu heb je de kans om te vechten tegen de trainers!<br/>
Klik op de knop "Trainer Battle" en ga de strijd aan met een trainer.<br /><br />

<button type='submit' name='submit' class='button'>Trainer Battle</button>
</from>

<br />
</center>
</form>