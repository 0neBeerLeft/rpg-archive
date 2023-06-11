<?php	
	$page = 'forum-categories';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
	#Als je wilt editten, het bericht ophalen
	if($_GET['editid'] != '') {
		$st = $db->prepare("SELECT categorie_naam, icoon_url FROM forum_categorieen WHERE categorie_id = :editid");
		$st->bindParam(':editid', $_GET['editid'], PDO::PARAM_INT);
		$st->execute();
		$edit = $st->fetch();
	}
	
	#Posten
	if((isset($_POST['submit'])) && ($_GET['editid'] == '')){
	
		$st = $db->prepare("SELECT categorie_naam FROM forum_categorieen WHERE categorie_naam = :categ_naam");
		$st->bindParam(':categ_naam', $_POST['naam'], PDO::PARAM_STR);
		$st->execute();
		$categ_naam = $st->fetch();
		
		if(empty($_POST['naam']))
			echo '<div class="red">'.$txt['alert_no_name'].'</div>';
			
		elseif(strlen($_POST['naam']) < 3)
			echo '<div class="red">'.$txt['alert_name_too_short'].'</div>';
			
		elseif(strlen($_POST['naam']) > 20)
			echo '<div class="red">'.$txt['alert_name_too_long'].'</div>';
			
		elseif(empty($_POST['icon_url']))
			echo '<div class="red">'.$txt['alert_no_icon'].'</div>';
		
		elseif(!file_exists($_POST['icon_url']))
			echo '<div class="red">'.$txt['alert_icon_doenst_exist'].'</div>';
		
		elseif($categ_naam >= 1)
			echo '<div class="red">'.$txt['alert_name_already_taken'].'</div>';

		else{
			$q = "INSERT INTO forum_categorieen (categorie_naam, auteur_naam, icoon_url) VALUES (:category_name, :username, :icon_url)";
			$st = $db->prepare($q);
			$st->bindParam(':category_name', $_POST['naam'], PDO::PARAM_STR);
			$st->bindParam(':username', $_SESSION['naam'], PDO::PARAM_STR);
			$st->bindParam(':icon_url', $_POST['icon_url'], PDO::PARAM_STR);
			$start = $st->execute();
			echo '<div class="green">'.$txt['success_add_category'].'</div>';
		}
	}
	#Bewerken
	elseif((isset($_POST['submit'])) && ($_GET['editid'] != '')){
	
		$st = $db->prepare("SELECT categorie_naam FROM forum_categorieen WHERE categorie_naam = :categ_naam");
		$st->bindParam(':categ_naam', $_POST['naam'], PDO::PARAM_STR);
		$st->execute();
		$categ_naam = $st->fetch();
		
		if(empty($_POST['naam']))
			echo '<div class="red">'.$txt['alert_no_name'].'</div>';
			
		elseif(strlen($_POST['naam']) < 3)
			echo '<div class="red">'.$txt['name_too_short'].'</div>';
			
		elseif(strlen($_POST['naam']) > 20)
			echo '<div class="red">'.$txt['name_too_long'].'</div>';
			
		elseif(empty($_POST['icon_url']))
			echo '<div class="red">'.$txt['alert_no_icon'].'</div>';
		
		elseif(!file_exists($_POST['icon_url']))
			echo '<div class="red">'.$txt['alert_icon_doesnt_exist'].'</div>';
		
		elseif($categ_naam >= 1)
			echo '<div class="red">'.$txt['alert_name_already_taken'].'</div>';

		else{
			$q = "UPDATE forum_categorieen SET categorie_naam = :category_name, icoon_url = :icon_url WHERE categorie_id = :editid";
			$st = $db->prepare($q);
			$st->bindParam(':category_name', $_POST['naam'], PDO::PARAM_STR);
			$st->bindParam(':editid', $_GET['editid'], PDO::PARAM_INT);
			$st->bindParam(':icon_url', $_POST['icon_url'], PDO::PARAM_STR);
			$start = $st->execute();
			echo '<div class="green">'.$txt['success_edit_category'].'</div>';
		}
	}
?>
<p><strong><?=GLOBALDEF_SITENAME?> forum</strong></p>
<table width="660" cellpadding="0" cellspacing="0">
	<tr>
		<?
		if($gebruiker['admin'] >= 1){ 
		echo '<td class="top_first_td" width="10"></td>';
		} 
		?>
    	<td class="top_first_td" width="40"><a class="atag"><?php echo $txt['#']; ?></a></td>
        <td class="top_td" width="200"><a class="atag"><?php echo $txt['name']; ?></a></td>
        <td class="top_td" width="110"><a class="atag"><?php echo $txt['threads']; ?></a></td>
        <td class="top_td" width="110"><a class="atag"><?php echo $txt['messages']; ?></a></td>
        <td class="top_td" width="200"><a class="atag"><?php echo $txt['last_post']; ?></a></td>
    </tr>
<?php

	$st = $db->prepare("SELECT forum_categorieen.*, gebruikers.username
						 FROM forum_categorieen
						 LEFT JOIN gebruikers
						 ON forum_categorieen.laatste_user_id = gebruikers.user_id
						 ORDER BY forum_categorieen.categorie_naam ASC");
	$st->execute();
	$query = $st->fetchAll();
	
	foreach($query as $info){
			
			#Datum-tijd goed
			$editimg = '';
			$datum = explode("-", $info['laatste_datum']);
			$tijd = explode(" ", $datum[2]);
			$datum = $tijd[0]."-".$datum[1]."-".$datum[0].",&nbsp;".$tijd[1];
			$datum_finished = substr_replace($datum ,"",-3);
			
			if($info['username'] == '') $last_post = $txt['nothing_posted'];
			else $last_post = '<a href="?page=profile&player='.$info['username'].'">'.$info['username'].'</a>: <span class="smalltext">'.$datum_finished.'</span>';
			if($gebruiker['admin'] == 1)
				$editimg = '<a href="?page=forum-categories&editid='.$info['categorie_id'].'"><img src="images/icons/edit.png" title="'.$txt['edit_category'].'" alt="Edit"></a>';
		echo '<tr>';
		if($gebruiker['admin'] >= 1){ 
		echo '<td><a href="?page=forum-categories&editid='.$info['categorie_id'].'"><img src="images/icons/edit.png" title="'.$info['categorie_naam'].'" alt="Edit" style="float:left;"></a></td>';
		} 
		echo '
				<td class="normal_first_td"><img src="'.$info['icoon_url'].'">'.$editimg.'</td>
				<td class="normal_td"><a href="?page=forum-threads&category='.$info['categorie_id'].'">'.$info['categorie_naam'].'</a></td>
				<td class="normal_td">'.$info['topics'].'</td>
				<td class="normal_td">'.$info['berichten'].'</td>
				<td class="normal_td">'.$last_post.'</td>
			  </tr>';
	}
?>
</table>

<?php if($gebruiker['admin'] >= 1){ ?>
<form method="post">
<HR />
<table width="660">
	<tr>
    	<td colspan="2"><img src="images/icons/page_add.png" width="16" height="16" /> <strong><?php echo $txt['add_category']; ?></strong></td>
    </tr>
    <tr>
    	<td width="125"><?php echo $txt['name_of_category']; ?></td>
        <td width="535"><input type="text" name="naam" class="text_long" maxlength="20" value="<?php if(isset($_POST['naam'])) echo $_POST['naam']; elseif($_GET['editid'] != '') echo $edit['categorie_naam']; ?>" /></td>
    </tr>
    <tr>
    	<td><?php echo $txt['icon_url']; ?></td>
        <td><input type="text" name="icon_url" class="text_long" maxlength="50" value="<?php if(isset($_POST['icon_url'])) echo $_POST['icon_url']; elseif($_GET['editid'] != '') echo $edit['icoon_url']; ?>" /></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td><input type="submit" name="submit" value="<?php echo $txt['button']; ?>" class="button" /></td>
    </tr>
</table>
</form>
<?php } ?>