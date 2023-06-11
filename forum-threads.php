<?php
	#Beveiliging, er moet wel een GET category zijn
	if($_GET['category'] == '') header('Location: ?page=forum-categories');
	
	$page = 'forum-threads';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
	$dirty_html_category = $_GET['category'];
	require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$category = $purifier->purify($dirty_html_category);
	
	$st = $db->prepare("SELECT categorie_naam FROM forum_categorieen WHERE categorie_id = :categorie_id");
	$st->bindParam(':categorie_id', $category, PDO::PARAM_INT);
	$st->execute();
	$prop = $st->fetch();
	if($prop == 0) header('Location: ?page=forum-categories');
	
	#Als je wilt editten, het bericht ophalen
	if($_GET['editid'] != '') {
	
		$dirty_html_editid = $_POST['editid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$editid = $purifier->purify($dirty_html_editid);
		
		$st = $db->prepare("SELECT topic_naam FROM forum_topics WHERE topic_id = :editid");
		$st->bindParam(':editid', $editid, PDO::PARAM_INT);
		$st->execute();
		$edit = $st->fetch();
		
	}
	
	#Posten
	if((isset($_POST['submit'])) && ($_GET['editid'] == '')){

		$dirty_html_category = $_GET['category'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$category = $purifier->purify($dirty_html_category);

		$dirty_html_naam = $_POST['naam'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$naam = $purifier->purify($dirty_html_naam);
		
		$st = $db->prepare("SELECT topic_naam FROM forum_topics WHERE topic_naam = :topic_naam AND categorie_id = :categorie_id");
		$st->bindParam(':topic_naam', $naam, PDO::PARAM_STR);
		$st->bindParam(':categorie_id', $naam, PDO::PARAM_INT);
		$st->execute();
		$topic_naam = $st->fetch();

		if(empty($_POST['naam']))
			echo '<div class="red">'.$txt['alert_no_name'].'</div>';

		elseif(strlen($_POST['naam']) < 3)
			echo '<div class="red">'.$txt['alert_name_too_short'].'</div>';

		elseif(strlen($_POST['naam']) > 20)
			echo '<div class="red">'.$txt['alert_name_too_long'].'</div>';

		elseif($topic_naam >= 1)
			echo '<div class="red">'.$txt['alert_name_already_taken'].'</div>';

		else{
			mysql_query("INSERT INTO forum_topics (categorie_id, topic_naam, auteur_naam) VALUES ('".$category."', '".$naam."', '".$_SESSION['naam']."')");
			mysql_query("UPDATE forum_categorieen SET topics = topics+'1' WHERE categorie_id = '".$category."'");
			echo '<div class="green">'.$txt['success_add_thread'].'</div>';
		}
	}
	#Bewerken
	elseif((isset($_POST['submit'])) && ($_GET['editid'] != '')){
	
		$dirty_html_category = $_GET['category'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$category = $purifier->purify($dirty_html_category);

		$dirty_html_naam = $_POST['naam'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$naam = $purifier->purify($dirty_html_naam);

		$dirty_html_editid = $_POST['editid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$editid = $purifier->purify($dirty_html_editid);

		if(empty($_POST['naam']))
			echo '<div class="red">'.$txt['alert_no_name'].'</div>';

		elseif(strlen($_POST['naam']) < 3)
			echo '<div class="red">'.$txt['alert_too_short'].'</div>';

		elseif(strlen($_POST['naam']) > 20)
			echo '<div class="red">'.$txt['alert_too_long'].'</div>';
			
		elseif(mysql_num_rows(mysql_query("SELECT topic_id FROM forum_topics WHERE topic_naam = '".$naam."' AND categorie_id = '".$category."'")) >= 1)
			echo '<div class="red">'.$txt['alert_name_already_taken'].'</div>';

		else{
			mysql_query("UPDATE forum_topics SET topic_naam = '".$naam."' WHERE topic_id = '".$editid."'");
			echo '<div class="green">'.$txt['success_edit_thread'].'</div>';
		}
	}
	#Status veranderen
	if(isset($_POST['status'])){
	

		$dirty_html_status = $_POST['status'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$status = $purifier->purify($dirty_html_status);

		$dirty_html_id = $_POST['id'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$eid = $purifier->purify($dirty_html_id);
	
		mysql_query("UPDATE forum_topics SET status = '".$status."' WHERE topic_id = '".$eid."'");
		echo '<div class="green">'.$txt['success_changed_status'].'</div>';
	}
?>
<p><a href="?page=forum-categories"><?php echo $txt['']; ?><?=GLOBALDEF_SITENAME?> forum</a> <img src="images/icons/arrow_right.png" width="16" height="16" style="margin-bottom:-3px;" /> <strong><?php echo $prop['categorie_naam']; ?></strong></p>
<table width="660" cellpadding="0" cellspacing="0">
  <tr>
    	<td class="top_first_td" width="40"><a class="atag"><?php echo $txt['#']; ?></a></td>
        <td class="top_td" width="200"><a class="atag"><?php echo $txt['title']; ?></a></td>
        <td class="top_td" width="110"><a class="atag"><?php echo $txt['maker']; ?></a></td>
		<td class="top_td" width="110"><a class="atag"><?php echo $txt['messages']; ?></a></td>
        <td class="top_td" width="200"><a class="atag"><?php echo $txt['last_post']; ?></a></td>
  </tr>
<?php

	#Pagina nummer opvragen
	if(empty($_GET['subpage'])) $subpage = 1; 
	else $subpage = $_GET['subpage']; 
	#Max aantal leden per pagina
	$max = 25; 
	
	$dirty_html_category = $_GET['category'];
	require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$category = $purifier->purify($dirty_html_category);
	
	$aantal = mysql_num_rows(mysql_query("SELECT topic_id FROM forum_topics WHERE categorie_id = '".$category."'"));
	$aantal_paginas = ceil($aantal/$max);
	if($aantal_paginas == 0) $aantal_paginas = 1;
	$pagina = $subpage*$max-$max; 

	$query = mysql_query("SELECT forum_topics.*, gebruikers.username
						 FROM forum_topics
						 LEFT JOIN gebruikers
						 ON forum_topics.laatste_user_id = gebruikers.user_id
						 WHERE forum_topics.categorie_id = '".$category."'
						 ORDER BY forum_topics.laatste_datum DESC, forum_topics.topic_naam ASC LIMIT ".$pagina.", ".$max."");
						 
	if(mysql_num_rows($query) == 0){
		echo '<tr>
				<td class="normal_first_td" colspan="5">'.$txt['no_threads'].'</td>
			  </tr>';
	}
	else{
		while($info = mysql_fetch_assoc($query)){
			
				#Datum-tijd goed
				$datum = explode("-", $info['laatste_datum']);
				$tijd = explode(" ", $datum[2]);
				$datum = $tijd[0]."-".$datum[1]."-".$datum[0].",&nbsp;".$tijd[1];
				$datum_finished = substr_replace($datum ,"",-3);
				
				if($info['username'] == '') $last_post = $txt['nothing_posted'];
				else $last_post = '<a href="?page=profile&player='.$info['username'].'">'.$info['username'].'</a>: <span class="smalltext">'.$datum_finished.'</span>';
				if($gebruiker['admin'] >= 1){
					if($info['status'] == 1) $status = '<form method="post">
      								<input type="hidden" name="status" value="0">
      								<input type="hidden" name="id" value="'.$info['topic_id'].'">
									<input type="image" src="images/icons/forum_open.png" alt="'.$txt['open_thread'].'" title="'.$txt['open_thread'].'" border="0" style="float:left;" />
									</form>';
					else $status = '<form method="post">
      								<input type="hidden" name="status" value="1">
      								<input type="hidden" name="id" value="'.$info['topic_id'].'">
									<input type="image" src="images/icons/forum_closed.png" alt="'.$txt['close_thread'].'" title="'.$txt['close_thread'].'" border="0" style="float:left;" />
									</form>';
									
					$status .= '<a href="?page=forum-threads&category='.$_GET['category'].'&subpage='.$_GET['subpage'].'&editid='.$info['topic_id'].'"><img src="images/icons/edit.png" title="'.$txt['edit_thread'].'" alt="Edit" style="float:left;"></a>';
				}
				else{
					if($info['status'] == 1) $status = '<img src="images/icons/forum_open.png" title="'.$txt['thread_is_open'].'" alt="Open">';
					else $status = '<img src="images/icons/forum_closed.png" title="'.$txt['thread_is_closed'].'" alt="Closed">';
				}

			#Bereken pagina
			$max = 15;
			$pagina = ceil($info['berichten']/$max);
			echo '<tr>
					<td class="normal_first_td">'.$status.'</td>
					<td class="normal_td"><a href="?page=forum-messages&category='.$info['categorie_id'].'&thread='.$info['topic_id'].'&subpage='.$pagina.'">'.$info['topic_naam'].'</a></td>
					<td class="normal_td"><a href="?page=profile&player='.$info['auteur_naam'].'">'.$info['auteur_naam'].'</a></td>
					<td class="normal_td">'.$info['berichten'].'</td>
					<td class="normal_td">'.$last_post.'</td>
				  </tr>';
		}
	}
	
#Paginasysteem
    $links = false;
    $rechts = false;
    echo '<tr><td colspan="5"><center><br /><div class="sabrosus">';
    if($subpage == 1)
      echo '<span class="disabled"> &lt; </span>';
    else{
      $back = $subpage-1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'&category='.$_GET['category'].'"> &lt; </a>';
    }
    for($i = 1; $i <= $aantal_paginas; $i++) { 
      if((2 >= $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif((2 >= $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'">'.$i.'</a>';
      elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'">'.$i.'</a>';
      else{
        $max = $subpage+3;
        $min = $subpage-3;  
        if($subpage == $i)
          echo '<span class="current">'.$i.'</span>';
        elseif(($min < $i) && ($max > $i))
        	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'">'.$i.'</a>';
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
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'&category='.$_GET['category'].'"> &gt; </a>';
    }
    echo "</div></center></td></tr>";
#Einde paginasysteem

?>
</table>

<?php if($_SESSION['naam'] != ''){ ?>
<form method="post">
<HR />
<table width="660">
	<tr>
    	<td colspan="2"><img src="images/icons/page_add.png" width="16" height="16" /> <strong><?php echo $txt['add_thread']; ?></strong></td>
    </tr>
    <tr>
    	<td width="125"><?php echo $txt['name_of_thread']; ?></td>
        <td width="535"><input type="text" name="naam" class="text_long" maxlength="20" value="<?php if(isset($_POST['naam'])) echo $_POST['naam']; elseif($_GET['editid'] != '') echo $edit['topic_naam']; ?>" /></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td><input type="submit" name="submit" value="<?php echo $txt['button']; ?>" class="button" /></td>
    </tr>
</table>
</form>
<?php } ?>