<?php
$page = 'rankinglist';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
?>
<center>
  <table width="660" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
      <td width="50" class="top_td">&nbsp;</td>
      <td width="180" class="top_td"><?php echo $txt['username']; ?></td>
      <td width="110" class="top_td"><?php echo $txt['country']; ?></td>
      <td width="180" class="top_td"><?php echo $txt['rank']; ?></td>
      <td width="90" class="top_td"><?php echo $txt['status']; ?></td>
    </tr>
    <?
    $expire = 60;
    $sql = "SELECT username, premiumaccount, online, rank, land, admin  FROM gebruikers WHERE account_code='1' ORDER BY rank DESC, rankexp DESC, username ASC";
    $records = query_cache($_GET['page'],$sql,$expire);
   
    //Pagina nummer opvragen
    if(empty($_GET['subpage'])) $subpage = 1; 
    else $subpage = $_GET['subpage']; 
    
    //Max aantal leden per pagina
    $max = 50; 
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
        //Default no medal
        $medaille = "&nbsp;";
        //Get Rank Info
        $rank = rank($row['rank']);

        //Kijken als je er zelf tussen zit, dan moet het dik gedrukt zijn.
        if($row['username'] == $_SESSION['naam']){
          $strong_l = "<strong>";
          $strong_r = "</strong>";
        }
    
        if($row['premiumaccount'] > 0) $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">'; 
    
        //Tijd voor plaatje
        if(($row['online']+300) > time()){
          $plaatje = "images/icons/status_online.png";
          $online  = $txt['online'];
        }
    
        if($subpage == 1){
      	  if($i == 1) $medaille = "<img src='images/icons/plaatsnummereen.png'>";
      	  elseif($i == 2) $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
      	  elseif($i == 3) $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
      	  elseif($i > 3 && $i <= 10) $medaille = "<img src='images/icons/gold_medaille.png'>";
      	  elseif($i > 10 && $i <= 30) $medaille = "<img src='images/icons/silver_medaille.png'>";
      	  elseif($i > 30 && $i <= 50) $medaille = "<img src='images/icons/bronze_medaille.png'>";
    	  }
        if (empty($row['land'])){
          $row['land'] = "Nederland";
        }
        if($row['admin'] == 1){
          $username = "<span style='color: blue;'><b>".$row['username']."</b></span>";
        }elseif($row['admin'] == 2){
          $username = "<span style='color: purple;'><b>".$row['username']."</b></span>";
        }elseif($row['admin'] == 3){
          $username = "<span style='color: red;'><b>".$row['username']."</b></span>";
        }else{
          $username = $row['username'];
        }
    
        echo '
          <tr>
            <td class="normal_first_td">'.$i.'.</td>
            <td class="normal_td">'.$medaille.'</td>
            <td class="normal_td">'.$strong_l.'<a href="?page=profile&player='.$row['username'].'">'.$username.$star.'</a>'.$strong_r.'</td>
            <td class="normal_td"><img src="images/flags/'.$row['land'].'.png" alt="'.$row['land'].'" title="'.$row['land'].'"></td>
            <td class="normal_td">'.$rank['ranknaam'].'</td>
            <td class="normal_td"><img src="'.$plaatje.'" width=18 height=15 />'.$online.'</td>
          </tr>
        ';
      }
      $i++;
    }
    echo '
      <tr>
        <td>&nbsp;</td>
      </tr>
    ';
  		
    //Pagina systeem
    $links = false;
    $rechts = false;
    echo '<tr><td colspan=6><center><div class="pagination">';
    if($subpage == 1)
      echo '<span class="disabled"> &lt; </span>';
    else{
      $back = $subpage-1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'"> &lt; </a>';
    }
    
    for($i = 1; $i <= $aantal_paginas; $i++){ 
      if((2 >= $i) && ($subpage == $i))
        echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
      elseif((2 >= $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
      elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
        echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
      elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
      else{
        $max = $subpage+3;
        $min = $subpage-3;  
        if($subpage == $i)
          echo '<span class="current">&nbsp;'.$i.'&nbsp;</span>';
        elseif(($min < $i) && ($max > $i))
        	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">&nbsp;'.$i.'&nbsp;</a>';
        else{
          if($i < $subpage){
            if(!$links){
              echo '<span class="disabled">...</span>';
              $links = True;
            }
          }
          else{
            if(!$rechts){
              echo '<span class="disabled">...</span>';
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
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'"> &gt; </a>';
    }
    echo "</div></center></td></tr>";
   ?>
  </table>
</center>