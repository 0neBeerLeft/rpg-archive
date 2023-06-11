<?		
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

#Admin controle
if($gebruiker['admin'] < 3){ header('location: index.php?page=home'); }
  
$premsql = mysql_query("SELECT * FROM premium_gekocht
					   ORDER BY premium_gekocht.datum DESC LIMIT 50");

?>
<table width="660">
	<tr>
    	<td width="60"><strong>ID:</strong></td>
        <td width="180"><strong>Pack:</strong></td>
        <td width="145"><strong>Who:</strong></td>
        <td width="105"><strong>Status:</strong></td>
        <td width="160"><strong>Date:</strong></td>
    </tr>
    <?php
	while($prem = mysql_fetch_assoc($premsql)){
		/*if($prem['premiumaccount'] > 0) $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid '.$prem['premiumaccount'].' days" title="Premiumlid '.$prem['premiumaccount'].' days" style="margin-bottom:-3px;">';
		else $star = '';
		*/
		echo'<tr>
				<td>'.$prem['id'].'</td>
				<td>'.$prem['wat'].'</td>
				<td><a href="?page=profile&player='.$prem['wie'].'">'.$prem['wie'].$star.'</a></td>
				<td>'.$prem['status'].'</td>
				<td>'.$prem['datum'].'</td>
			</tr>';
	}
	?>
</table>