<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'bank';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
	
#ALs er al een speler naam binnenkomt met een GET, deze laden
if(isset($_GET['player'])) $spelernaam = $_GET['player'];
else $spelernaam = $_POST['gebruiker'];
#Als er silver of gold naar clan wordt gestuurd


?>
<script language="JavaScript" type="text/javascript" src="javascripts/numeriek.js"></script>
<center><h3>Referral System 1.0</h3></center>
<hr>
List is showing you who registered using ur name as Referrer<br>
You get 2 gold every time user registers using ur name as Referrer.<br>
<br>
<table width="300" border="0">
      <tr>
        <td>Your Referral Link:</td>
        <td colspan="2"><input type="text" name="referral" value="<? echo GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/index.php?page=register&referer='.$_SESSION['naam'].'"' ?>  class="text_long" size="30" disabled /></td>
      </tr>
</table>

<hr>
<center><h3>Members you referred</h3></center>

<center>
  <table width="660" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50" class="top_first_td">#</td>
      <td width="50" class="top_td">&nbsp;</td>
      <td width="180" class="top_td">Player</td>
      <td width="110" class="top_td">Country</td>
      <td width="90" class="top_td">Status</td>
    </tr>
    <?
    $expire = 1;
    $sql = "SELECT username, premiumaccount, online, land, referer  FROM gebruikers WHERE referer ='".$gebruiker['username']."'";
    $records = query_cache($_GET['page'],$sql,$expire);
   
    //Pagina nummer opvragen
    if(empty($_GET['subpage'])) $subpage = 1; 
    else $subpage = $_GET['subpage']; 
    
    //Max aantal leden per pagina
    $max = 100; 
    //Leden tellen
    $aantal_paginas = ceil(count($records)/$max);
    $pagina = $subpage*$max-$max; 

    $i = 1;
    foreach ($records as $id=>$row) {   
      if(($i >= $pagina+1) AND ($pagina+$max+1 > $i)){
        //Default no strong
        $strong_l = "";
        $strong_r = "";
        //Default no star
        $star = '';
        //Default offline
        $plaatje = "images/icons/status_offline.png";
        $online  = $txt['offline'];

        
        //Kijken als je er zelf tussen zit, dan moet het dik gedrukt zijn.
        if($row['username'] == $_SESSION['naam']){
          $strong_l = "<strong>";
          $strong_r = "</strong>";
        }
    
        if($row['premiumaccount'] > 0) $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premium" title="Premium" style="margin-bottom:-3px;">'; 
    
        //Tijd voor plaatje
        if(($row['online']+300) > time()){
          $plaatje = "images/icons/status_online.png";
          $online  = $txt['online'];
        }

        echo '
          <tr>
            <td class="normal_first_td">'.$i.'.</td>
            <td class="normal_td">&nbsp;</td>
            <td class="normal_td">'.$strong_l.'<a href="?page=profile&player='.$row['username'].'">'.$row['username'].$star.'</a>'.$strong_r.'</td>
            <td class="normal_td"><img src="images/flags/'.$row['land'].'.png" alt="'.$row['land'].'" title="'.$row['land'].'"></td>
            <td class="normal_td"><img src="'.$plaatje.'" width=18 height=15 />'.$online.'</td> 
          </tr>
        ';
      }
      $i++;
    }
    echo '
      <tr>
        <td>&nbsp;</td>
      </tr></table>
    ';
    ?>
