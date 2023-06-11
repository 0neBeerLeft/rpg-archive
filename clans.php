<?php
//Security laden
include('includes/security.php');

$page = 'clans';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');


//clans laden
$number = 0;
$clanquery = mysql_query("SELECT * FROM clans ORDER BY clan_level DESC, clan_spelersaantal DESC, clan_naam ASC") or die(mysql_error());
//hier komt die niet verder, GEEN or die achterzetten anders doet deze het niet
$tel = mysql_num_rows($clanquery);

	
if($tel == 0 )
{ 
echo "<div class='red'>".$txt['no_clans']."</div>";
}
else
{
?>
<center>
<p><?php echo $txt['title_text']; ?></p>

<table width="450" cellpadding="0" cellspacing="0">
	<tr>
    	<td class="top_first_td" width="50"><?php echo '#'; ?></td>
        <td class="top_td" class="200"><?php echo 'Name'; ?></td>
        <td class="top_td" class="100"><?php echo 'Players'; ?></td>
        <td class="top_td" class="100"><?php echo 'Owner'; ?></td>
		<td class="top_td" class="100"><?php echo 'Level'; ?></td>
    </tr>
 <?php 
  while($clan = mysql_fetch_array($clanquery))
{
//Kijken als je er zelf tussen zit, dan moet het dik gedrukt zijn.
        if($clan['clan_owner_name'] == $_SESSION['naam']){
          $strong_l = "<strong>";
          $strong_r = "</strong>";
		 }
		 else{
		 $strong_l = "";
         $strong_r = "";
        }
		
	 	$number ++;
		if($clan['premiumaccount'] > 0) $premiumimg = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
	 	echo '<tr>
				<td class="normal_first_td">'.$number.'.</td>
				<td class="normal_td"><a href="?page=clan-profile&clan='.$clan['clan_naam'].'">'.$clan['clan_naam'].'</a></td>
				<td class="normal_td">'.$clan['clan_spelersaantal'].'</td>
				<td class="normal_td">'.$strong_l.'<a href="?page=profile&player='.$clan['clan_owner'].'">'.$clan['clan_owner'].'</a>'.$strong_r.'</td>
				<td class="normal_td">'.$clan['clan_level'].'</td>
			  </tr>';
 }
?>
</table>
</center>
 <?php 
 } 
 ?>