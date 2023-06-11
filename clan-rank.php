<?php
$page = 'rankinglist';
include_once('language/language-pages.php');

$number = 0;
$clanquery = mysql_query("SELECT * FROM clans ORDER BY clan_level DESC, clan_spelersaantal DESC, clan_naam ASC") or die(mysql_error());

$tel = mysql_num_rows($clanquery);

   if(empty($_GET['subpage'])) $subpage = 1; 
    else $subpage = $_GET['subpage']; 
    
    //Max clans per page
    $max = 50; 
    $aantal_paginas = ceil(count($records)/$max);
    $pagina = $subpage*$max-$max; 
?>

<center>

  <table width="660" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
      <td width="50" class="top_td">&nbsp;</td>
      <td width="130" class="top_td">Clan Name</td>
      <td width="90" class="top_td">Friends</td>
      <td width="90" class="top_td">Owner</td>
      <td width="90" class="top_td">Clan Level</td>
      <td width="90" class="top_td">Gold</td>
      <td width="90" class="top_td">Silver</td>
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
                if($subpage == 1){
            if($number == 1) $medaille = "<img src='images/icons/plaatsnummereen.png'>";
            elseif($number == 2) $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
            elseif($number == 3) $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
            elseif($number > 3 && $number <= 10) $medaille = "<img src='images/icons/gold_medaille.png'>";
            elseif($number > 10 && $number <= 30) $medaille = "<img src='images/icons/silver_medaille.png'>";
            elseif($number > 30 && $number <= 50) $medaille = "<img src='images/icons/bronze_medaille.png'>";
          }
        if($clan['premiumaccount'] > 0) $premiumimg = '<img src="https://forum.ragezone.com/images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
         echo '<tr>
                <td class="normal_first_td">'.$number.'.</td>
                            <td class="normal_td">'.$medaille.'</td>
                <td class="normal_td"><a href="?page=clan-profile&clan='.$clan['clan_naam'].'">'.$clan['clan_naam'].'</a></td>
                <td class="normal_td">'.$clan['clan_spelersaantal'].'</td>
                <td class="normal_td">'.$strong_l.'<a href="?page=profile&player='.$clan['clan_owner'].'">'.$clan['clan_owner'].'</a>'.$strong_r.'</td>
                <td class="normal_td">'.$clan['clan_level'].'</td>
                <td class="normal_td">'.$clan['clan_gold'].' <img src="/images/icons/gold.png"></td>
                <td class="normal_td">'.$clan['clan_silver'].' <img src="/images/icons/silver.png"></td>
              </tr>';
 }
?>
  </table>
</center>
