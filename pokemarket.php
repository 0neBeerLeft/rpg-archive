<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'promotion';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>
<html>
<div class="content">
<div style="float: right;">
<img src="./images/market.gif">
</div>
	<br/><br/><br/>

<a href="?page=market&shopitem=balls"><h1>Pok&eacute;balls</h1></a>
<a href="?page=market&shopitem=potions"><h1>Potions</h1></a>
<a href="?page=market&shopitem=items"><h1>Items</h1></a>
<a href="?page=market&shopitem=specialitems"><h1>Vitamins</h1></a>
<a href="?page=market&shopitem=stones"><h1>Stones</h1></a>
<a href="?page=market&shopitem=pokemon"><h1>Eggs</h1></a>
<?php if($gebruiker['rank'] > 4){ echo '<a href="?page=market&shopitem=attacks"><h1>Aanvallen</h1></a>'; } ?>


</div>

</html>