<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 1) header('location: index.php?page=home');
?>


<table width="600">
	<tr>
		<td width="50">#</td>
		<td width="150"><strong>Naam:</strong></td>
		<td width="150"><strong>Wachtwoord:</strong></td>
		<td width="150"><strong>Ip:</strong></td>
		<td width="100"><strong>Ban:</strong></td>
	</tr>

<?php
//Alle buddy's tellen voor de pagina
$aantalfout = mysql_num_rows(mysql_query("SELECT `id` FROM `inlog_fout`"));

//Pagina nummer opvragen
if(empty($_GET['subpage'])){ 
  $subpage = 1; 
} 
else{ 
  $subpage = $_GET['subpage']; 
} 
//Max aantal leden per pagina
$max = 50; 
//Leden tellen        
$pagina = $subpage*$max-$max; 
$aantal_paginas = ceil($aantalfout/$max);

//Buddy's laden
$fout = mysql_query("SELECT `spelernaam`, `wachtwoord`, `ip`, `datum` FROM `inlog_fout` ORDER BY `id` DESC LIMIT ".$pagina.", ".$max."");

for($j=$pagina+1; $foutgegevens = mysql_fetch_array($fout); $j++){
  //Scherm weergave  
  echo '<tr>
  				<td height="30">'.$j.'.</td>
  				<td><a href="index.php?page=profile&player='.$foutgegevens['spelernaam'].'">'.$foutgegevens['spelernaam'].'</a></td>
  				<td>'.$foutgegevens['wachtwoord'].'</a></td>
  				<td>'.$foutgegevens['ip'].'</a></td>
  				<td><a href="index.php?page=admin/ban-user&ip='.$foutgegevens['ip'].'">Ban</a></td>
  			</tr>';
}

		  //Pagina systeem
          $links = false;
          $rechts = false;
          echo '<tr><td colspan=5><center><div class="sabrosus">';
          if($subpage == '1'){
            echo '<span class="disabled"> &lt; </span>';
          }
          else{
            $back = $subpage-1;
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'"> &lt; </a>';
          }
          for($i = 1; $i <= $aantal_paginas; $i++) 
          { 
              
            if((2 >= $i) && ($subpage == $i)){
              echo '<span class="current">'.$i.'</span>';
            }
            elseif((2 >= $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage == $i)){
              echo '<span class="current">'.$i.'</span>';
            }
            elseif(($aantal_paginas-2 < $i) && ($subpage != $i)){
              echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
            }
            else{
              $max = $subpage+3;
              $min = $subpage-3;  
              if($subpage == $i){
                echo '<span class="current">'.$i.'</span>';
              }
              elseif(($min < $i) && ($max > $i)){
              	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'">'.$i.'</a>';
              }
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
          if($aantal_paginas == $subpage){
            echo '<span class="disabled"> &gt; </span>';
          }
          else{
            $next = $subpage+1;
            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'"> &gt; </a>';
          }
          echo "</div></center>
		  		</td>
		  	</tr>
		  </table>";
?>