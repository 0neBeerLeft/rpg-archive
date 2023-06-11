<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 1) header('location: index.php?page=home');

################################################################

        //ALS ER MEER LEDEN ZIJN, PAGINA SYSTEEM!!
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
        $aantal_leden = mysql_num_rows(mysql_query("SELECT id FROM berichten")); 
        $aantal_paginas = ceil($aantal_leden/$max); 
        
        $pagina = $subpage*$max-$max;
		
		
//Alle berichten laden
$messagesquery = mysql_query("SELECT berichten.afzender_id, berichten.ontvanger_id, berichten.bericht, berichten.onderwerp, gebruikers.username
							 FROM berichten
							 INNER JOIN gebruikers
							 ON afzender_id = gebruikers.user_id
							 ORDER BY id DESC LIMIT ".$pagina.", ".$max."");
  //Als er meer dan 1 bericht is
	if (mysql_num_rows($messagesquery) >= 1)
	{  
	  //Scherm weergave
  	echo '<table width=600" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100"><b>Van</b></td>
				<td width="100"><b>Naar</b></td>
				<td width="100"><b>Onderwerp</b></td>
				<td width="300"><b>Bericht</b></td>
			</tr>
			<tr>
				<td colspan="4"><HR></td>
			</tr>';
			
		//Alle berichten laten zien
		while ($row = mysql_fetch_array($messagesquery))
        {
			 $j++;
			//Enters in de textarea ook weergeven als een enter
      		$tekst = $row['bericht'];
            $ontvangert = mysql_result(mysql_query("SELECT username
							 FROM gebruikers
							 where user_id = ".$row['ontvanger_id'].""),0);

			echo'<tr>
					<td><a href="index.php?page=profile&player='.$row['username'].'">'.$row['username'].'</a></td>
					<td><a href="index.php?page=profile&player='.$ontvangert.'">'.$ontvangert.'</a></td>
					<td>'.$row['onderwerp'].'</td>
					<td><table width="300" border="0">
							<tr>
								<td>'.$tekst.'</td>
							</tr>
						</table></td>
				</tr>
				<tr>
					<td colspan="4"><HR></td>
				</tr>';
		}
		
		  //Pagina systeem
          $links = false;
          $rechts = false;
          echo '<tr><td colspan=4><center><div class="sabrosus">';
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
	}
?>