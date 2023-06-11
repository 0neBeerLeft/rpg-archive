<?PHP
	include "check.php";
	include "config.php";
	
	if($data->rang != 3 )
	{
		Loading(3, "begin");
		return;
	}	
	require_once "tekst.php";
	?>
    
    <div class="titel"> Admin add news </div>
    
    <?PHP
	if(isset($_POST['update']))
	{
		if($data->rang != 3 )
		exit;
		if(empty($_POST['titel']) || empty( $_POST['content']))
		Fout("Een van de opgegeven velden is leeg");
		else
		{
			$sql = "INSERT INTO `crimz_news` (`door`,`datum`,`titel`,`bericht`) VALUES('".$data->login."',NOW(),'".$_POST['titel']."','".	$_POST['content']	."')";
			
			if(mysql_query($sql))
			Goed("Bericht geplaast");
			else
			Fout("Bericht kon niet worden geplaast");
			
		}
	}
	else
	{
	
	
?>

<div class="lijst">
 <form action=""  method="post">
Titel<input type="text" name="titel" value="" >
<textarea style='width: 400px; height:200px' name='content'></textarea>
<input  type="submit" name="update" value="Plaats" >
</form>
</div>
<?PHP
	}
?>