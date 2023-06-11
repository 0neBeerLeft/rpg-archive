<?php
include('includes/security.php');

#Kijken of je wel pokemon bij je hebt
if($gebruiker['in_hand'] == 0) header('location: index.php');

$page = 'attack/gyms';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if($gebruiker['item_over'] < 1)
	echo '<div class="blue">'.$txt['alert_itemplace'].'</div>';

$gymsql = mysql_query("SELECT * FROM trainer WHERE wereld ='".$gebruiker['wereld']."' ORDER BY id ASC");
$trainer = mysql_fetch_assoc(mysql_query("SELECT * FROM gebruikers_badges WHERE `user_id`='".$_SESSION['id']."'"));

if(isset($_POST['submit'])){
  if($gebruiker['in_hand'] == 0){
    echo '<div class="blue">'.$txt['no_pokemon'].'</div>';
  }
  else{
    $gym_info = mysql_fetch_assoc(mysql_query("SELECT `rank`, `wereld`, `badge` FROM `trainer` WHERE `naam`='".$_POST['gym_leader']."'"));
    if($gebruiker['rank'] < $gym_info['rank'])
  	  echo "<div class='blue'>".$txt['alert_rank_too_less']."</div>";
    elseif($gebruiker['wereld'] != $gym_info['wereld'])
  	  echo "<div class='red'>".$txt['alert_wrong_world']."</div>";
    elseif($badgecheck[$gym_info['badge']] == 1)
  	  echo "<div class='blue'>".$txt['alert_gym_finished']."</div>";
    else{
      include('attack/trainer/trainer-start.php');
      mysql_data_seek($pokemon_sql, 0);
      $opzak = mysql_num_rows($pokemon_sql);
      $level = 0;
      while($pokemon = mysql_fetch_assoc($pokemon_sql)) $level += $pokemon['level'];
      $trainer_ave_level = $level/$opzak;
      #Make Fight
      $info = create_new_trainer_attack($_POST['gym_leader'],$trainer_ave_level,$_POST['gebied']);
      if(empty($info['bericht'])) header("Location: ?page=attack/trainer/trainer-attack");
      else echo '<div class="red"><img src="images/icons/red.png"> '.$txt[$info['bericht']].'</div>';
    }
  }
}
?>
<center>
<table width="600">
	<tr>
    <td><center>
    <?php 
      while($gym = mysql_fetch_assoc($gymsql)){
        if($trainer[$gym['badge']] == 1){
        	$status = 'finished';
        	$form1 = '';
        	$form2 = '';
        	$button = '<img src="images/icons/green.png" alt="Gehaald" title="Gehaald" style="margin-bottom: -3px;"> '.$txt['finished'];
        }
        elseif($gebruiker['rank'] < $gym['rank']){
        	$status = 'notyet';
        	$form1 = '';
        	$form2 = '';
        	$button = '<img src="images/icons/verwijder.png" alt="Rank te laag" title="Rank te laag" style="margin-bottom: -3px;"> '.$txt['rank_too_less'];
        }
        else{
        	$status = 'available';
        	$form1 = '<form method="post" action="?page=attack/gyms">';
        	$form2 = '</form>';
        	$button = '<button type="submit" name="submit" class="button" style="min-width: 165px;">Daag uit</button>';
        }
        				
        echo $form1.'<table width="290" height="100" class="'.$status.'">
        		<tr>
        			<td width="110" rowspan="4"><center><img src="images/badges/'.$gym['badge'].'.png" style="margin-bottom:5px;" /><br /><span class="smalltext">'.$gym['badge'].' Badge</span></center></td>
        			<td width="180"><strong>'.$gym['gymnaam'].' Gym</strong></td>
        		</tr>
        		<tr>
        			<td>'.$txt['leader'].' '.$gym['naam'].'</td>
        		</tr>
        		<tr>
        			<td>'.$txt['from_rank'].' <strong>'.$gym['rank'].'</strong></td>
        		</tr>
        		<tr>
        			<td><input type="hidden" name="gym_leader" value="'.$gym['naam'].'">'.$button.'
        	</table>'.$form2;
      }
			?>
    </center></td>
  </tr>
</table>
</center>