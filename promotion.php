<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	$page = 'promotion';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
?>

<?php echo $txt['promotion_text']; ?>