<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'search-user';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>

<form method="post">
  <center>
    <table width="660" border="0">
      <tr>
        <td><center><?php echo $txt['title_text']; ?><br /><br /></center></td>
      </tr>
      <tr>
        <td>
          <center>
            <table width="300" border="0">
              <tr>
                <td><strong><?php echo $txt['username']; ?></strong></td>
                <td><input name="username" type="text" value="" class="text_long" /></td>
                <td><input name="submit1" type="submit" value="<?php echo $txt['button']; ?>" class="button_mini" /></td>
              </tr>
            </table>
          </center>
        </td>
    </tr>
    </table>
  </center>
</form>
<br />

<?
//als er een geen naam in de post zit
if(isset($_GET['player'])) $_POST['username'] = $_GET['player'];

//Pagina nummer opvragen
$subpage = 1;
if(isset($_GET['subpage'])) $subpage = $_GET['subpage']; 
 
//Als er word gezocht op spelernaam
if(isset($_POST['username'])){
  //Max aantal leden per pagina
  $max = 50; 
  $pagina = $subpage*$max-$max;
  //Spelernaam schoonmaken, en er een % teken achterzetten voor het zoek systeem
  $spelernaam = $_POST['username'];
  //Leden tellen
  $query		= mysql_query("SELECT `user_id` FROM gebruikers WHERE account_code='1' AND `username`='".$spelernaam."'");
  $aantal_leden = mysql_num_rows($query); 
  $aantal_paginas = ceil($aantal_leden/$max); 
  if($aantal_paginas == 0) $aantal_paginas = 1;
  //Is er wel een speler naam op gegeven, zo ja dan verder
  echo '
  	<table width="660" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" class="top_first_td">'.$txt['#'].'</td>
        <td width="180" class="top_td">'.$txt['username'].'</td>
        <td width="110" class="top_td">'.$txt['country'].'</td>
        <td width="180" class="top_td">'.$txt['rank'].'</td>
        <td width="120" class="top_td">'.$txt['status'].'</td>
      </tr>';
  
	//Gegevens laden van het ingetoetste spelernaam
	$dbres = mysql_query("SELECT user_id, username, wereld, online, rank, land, premiumaccount FROM gebruikers WHERE account_code='1' AND `username`='".$spelernaam."' ORDER BY username LIMIT ".$pagina.", ".$max."");
    if (empty($dbres['land'])){
      $dbres['land'] = "Nederland";
    }

  //Lijst opbouwen per lid gaat vanzelf
  for($j=$pagina+1; $gegevens = mysql_fetch_array($dbres); $j++){
    $strong = '';
    $strongg = '';
    $star = '';
    $plaatje = "images/icons/status_offline.png";
    $online  = $txt['offline'];
    
    //Kijken als je er zelf tussen zit, dan moet het dik gedrukt zijn.
    if($gegevens['username'] == $_SESSION['naam']){
      //Strong mee geven, moet zo omdat anders de link niet werkt
      $strong   = "<strong>";
      $strongg  = "</strong>";
    }
  
    if($gegevens['premiumaccount'] > 0)
      $star  = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
  
    //Tijd voor plaatje
    if(($gegevens['online']+300) > time()){
      $plaatje = "images/icons/status_online.png";
      $online  = $txt['online'];
    }
  
	  $rank = rank($gegevens['rank']);
        
	  echo '
      <tr>
        <td class="normal_first_td">'.$j.'.</td>
        <td class="normal_td">'.$strong.'<a href="?page=profile&player='.$gegevens['username'].'">'.$gegevens['username'].$star.'</a>'.$strongg.'</td>
        <td class="normal_td"><img src="images/flags/'.$gegevens['land'].'.png"></td>
        <td class="normal_td">'.$rank['ranknaam'].'</td>
        <td class="normal_td"><img src="'.$plaatje.'" width="18" height="15" />'.$online.'</td>
      </tr>';
  }
  echo "<tr><td>&nbsp;</td></tr>";
  
  //Pagina systeem
  $links = false;
  $rechts = false;
  echo '<tr><td colspan=6><center><div class="sabrosus">';
  if($subpage == 1)
    echo '<span class="disabled"> &lt; </span>';
  else{
    $back = $subpage-1;
    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'&player='.$_POST['username'].'"> &lt; </a>';
  }
  for($i = 1; $i <= $aantal_paginas; $i++){ 
    if((2 >= $i) && ($subpage == $i))
      echo '<span class="current">'.$i.'</span>';
    elseif((2 >= $i) && ($subpage != $i))
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&player='.$_POST['username'].'">'.$i.'</a>';
    elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
      echo '<span class="current">'.$i.'</span>';
    elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&player='.$_POST['username'].'">'.$i.'</a>';
    else{
      $max = $subpage+3;
      $min = $subpage-3;  
      if($subpage == $i)
        echo '<span class="current">'.$i.'</span>';
      elseif(($min < $i) && ($max > $i))
      	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&player='.$_POST['username'].'">'.$i.'</a>';
      else{
        if($i < $subpage){
          if(!$links){
            echo '...';
            $links = True;
          }
        }
        else{
          if(!$rechts){
            echo '...';
            $rechts = True;
          }
        }
      }
    }
  } 
  if($aantal_paginas == $subpage)
    echo '<span class="disabled"> &gt; </span>';
  else{
    $next = $subpage+1;
    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'&player='.$_POST['username'].'"> &gt; </a>';
  }
  echo '</div></center></td></tr>
</table>';
}
?>