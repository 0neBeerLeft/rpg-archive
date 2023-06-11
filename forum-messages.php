<?php

	#Beveiliging, er moet wel een GET category zijn
	if(($_GET['category'] == '') OR ($_GET['thread'] == '')) header('Location: ?page=forum-categories');
	
	$page = 'forum-messages';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
	$dirty_html_thread = $_GET['thread'];
	require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$thread = $purifier->purify($dirty_html_thread);

	$st = $db->prepare("SELECT forum_topics.topic_naam, forum_topics.status, forum_categorieen.categorie_id, forum_categorieen.categorie_naam
						FROM forum_topics
						INNER JOIN forum_categorieen
						ON forum_topics.categorie_id = forum_categorieen.categorie_id
						WHERE forum_topics.topic_id = :thread");
	$st->bindParam(':thread', $thread, PDO::PARAM_INT);
	$st->execute();
	$prop = $st->fetch();

	if($prop == false) header('Location: ?page=forum-categories');
	
	#Bericht Posten (dus niet iets bewerken of verwijderen)
	if((isset($_POST['submit'])) && ($_GET['editid'] == '') && ($_GET['deleteid'] == '')){
		
		$dirty_html_tekst = $_POST['tekst'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$tekst = $purifier->purify($dirty_html_tekst);

		$dirty_html_thread = $_GET['thread'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$thread = $purifier->purify($dirty_html_thread);

		$dirty_html_category = $_GET['category'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$category = $purifier->purify($dirty_html_category);
		
		$rowCount = $db->prepare("SELECT * FROM forum_berichten 
							WHERE bericht = :tekst 
							AND topic_id = :thread 
							AND user_id = :sess_id");
		$rowCount->bindParam(':tekst', $tekst, PDO::PARAM_STR);
		$rowCount->bindParam(':thread', $thread, PDO::PARAM_INT);
		$rowCount->bindParam(':sess_id', $_SESSION['id'], PDO::PARAM_INT);
		$rowCount->execute();
		$rows = $rowCount->rowCount();
		
		if(empty($tekst)) {
			$error = '<div class="red">' . $txt['alert_no_text'] . '</div>';

		}elseif($rows >= 1)
			$error = '<div class="red">'.$txt['alert_already_send'].'</div>';

		else{
			$datum = date('Y-m-d H:i:s');
			$q = "INSERT INTO forum_berichten (categorie_id, topic_id, user_id, bericht, datum) VALUES (:category, :thread, :sess_id, :tekst, :datum);
		          UPDATE forum_categorieen SET berichten = berichten + '1', laatste_user_id = :sess_id, laatste_datum = :datum WHERE categorie_id = :category;
		          UPDATE forum_topics SET berichten = berichten + '1', laatste_user_id = :sess_id, laatste_datum = :datum WHERE topic_id = :thread";
			$st = $db->prepare($q);
			$st->bindParam(':category', $category, PDO::PARAM_INT);
			$st->bindParam(':thread', $thread, PDO::PARAM_INT);
			$st->bindParam(':sess_id', $_SESSION['id'], PDO::PARAM_INT);
			$st->bindParam(':tekst', $tekst, PDO::PARAM_STR);
			$st->bindParam(':datum', $datum, PDO::PARAM_STR);
			$start = $st->execute();
			
			$error = '<div class="green">'.$txt['success_post_message'].'</div>';
		}
	}
	#Bewerken
	elseif((isset($_POST['submit'])) && ($_GET['editid'] != '')){
	
		$dirty_html_editid = $_GET['editid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$editid = $purifier->purify($dirty_html_editid);
		
		$dirty_html_tekst = $_POST['tekst'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$tekst = $purifier->purify($dirty_html_tekst);
	
		$rowCount = $db->prepare("SELECT * FROM forum_berichten WHERE id = :editid");
		$rowCount->bindParam(':editid', $editid, PDO::PARAM_INT);
		$rowCount->execute();
		$rowsEdit = $rowCount->rowCount();
		
		if(empty($_POST['tekst']))
			$error = '<div class="red">'.$txt['alert_no_text'].'</div>';
		
		elseif(!$gebruiker['admin'] >= 1)
			$error = '<div class="red">'.$txt['alert_not_admin'].'</div>';
		
		elseif($rowsEdit == 0)
			$error = '<div class="red">'.$txt['alert_message_doesnt_exist'].'</div>';
		
		else{
			$st = $db->prepare("UPDATE forum_berichten SET bericht = :tekst WHERE id = :editid");
			$st->bindParam(':editid', $editid, PDO::PARAM_INT);
			$st->bindParam(':tekst', $tekst, PDO::PARAM_STR);
			$st->execute();
			$error = '<div class="green">'.$txt['success_edit_message'].'</div>';
		}
	}
	#Verwijderen
	elseif($_GET['deleteid'] != ''){
	
		$dirty_html_deleteid = $_GET['deleteid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$deleteid = $purifier->purify($dirty_html_deleteid);
		
		$dirty_html_thread = mysql_escape_string($_GET['thread']);
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$thread = $purifier->purify($dirty_html_thread);
		
		$dirty_html_category = $_GET['category'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$category = $purifier->purify($dirty_html_category);
		
		$rowCount = $db->prepare("SELECT * FROM forum_berichten WHERE id = :deleteid");
		$rowCount->bindParam(':deleteid', $deleteid, PDO::PARAM_INT);
		$rowCount->execute();
		$rowsDelete = $rowCount->rowCount();
		
		if(!$gebruiker['admin'] >= 1)
			echo '<div class="red">'.$txt['alert_not_admin'].'</div>';
		
		elseif($rowsDelete == 0)
			echo '<div class="red">'.$txt['alert_message_doesnt_exist'].'</div>';
		
		else{
			mysql_query("DELETE FROM forum_berichten WHERE id = '".$deleteid."'");
			mysql_query("UPDATE forum_categorieen SET berichten = berichten - '1' WHERE categorie_id = '".$category."'");
			mysql_query("UPDATE forum_topics SET berichten = berichten - '1' WHERE topic_id = '".$thread."'");
			
			$q = "DELETE FROM forum_berichten WHERE id = :deleteid;
		          UPDATE forum_categorieen SET berichten = berichten - '1' WHERE categorie_id = :category;
		          UPDATE forum_topics SET berichten = berichten - '1' WHERE topic_id = :thread";
			$st = $db->prepare($q);
			$st->bindParam(':category', $category, PDO::PARAM_INT);
			$st->bindParam(':thread', $thread, PDO::PARAM_INT);
			$st->bindParam(':deleteid', $deleteid, PDO::PARAM_INT);
			$start = $st->execute();
			
			
			echo '<div class="green">'.$txt['success_message_delete'].'</div>';
		}
	}
	
#Paginasysteem dingen
if(empty($_GET['subpage'])) $subpage = 1; 
else $subpage = $_GET['subpage']; 
#Max aantal leden per pagina
$max = 15; 

$dirty_html_thread = mysql_escape_string($_GET['thread']);
require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$thread = $purifier->purify($dirty_html_thread);

$dirty_html_category = $_GET['category'];
require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$category = $purifier->purify($dirty_html_category);

$rowCount = $db->prepare("SELECT id FROM forum_berichten WHERE categorie_id = :category AND topic_id = :thread");
$rowCount->bindParam(':category', $category, PDO::PARAM_INT);
$rowCount->bindParam(':thread', $thread, PDO::PARAM_INT);
$rowCount->execute();
$rowsNumber = $rowCount->rowCount();

$aantal = $rowsNumber;
$aantal_paginas = ceil($aantal/$max);
if($aantal_paginas == 0) $aantal_paginas = 1;
$pagina = $subpage*$max-$max;
?>

<p><a href="?page=forum-categories"><?=GLOBALDEF_SITENAME?> forum</a> <img src="images/icons/arrow_right.png" width="16" height="16" style="margin-bottom:-3px;" /> <a href="?page=forum-threads&category=<?php echo $prop['categorie_id']; ?>"><?php echo $prop['categorie_naam']; ?></a> <img src="images/icons/arrow_right.png" width="16" height="16" style="margin-bottom:-3px;" /> <strong><?php echo $prop['topic_naam']; ?></strong><br /></p>
<?php if($_SESSION['naam'] == '') echo $txt['you_must_be_online'].'<br /><br />';
elseif($prop['status'] == 0) echo $txt['topic_closed'].'<br /><br />';

#Paginasysteem
    $links = false;
    $rechts = false;
    echo '<table width="660">
			<tr>
				<td><center><br /><div class="sabrosus">';
    if($subpage == 1)
      echo '<span class="disabled"> &lt; </span>';
    else{
      $back = $subpage-1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'"> &lt; </a>';
    }
    for($i = 1; $i <= $aantal_paginas; $i++) { 
      if((2 >= $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif((2 >= $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
      elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
      else{
        $max = $subpage+3;
        $min = $subpage-3;  
        if($subpage == $i)
          echo '<span class="current">'.$i.'</span>';
        elseif(($min < $i) && ($max > $i))
        	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
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
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'"> &gt; </a>';
    }
    echo "</div></center></td>
		</tr>
	</table>";
#Einde paginasysteem
?>
<HR />
<?php
	#Als iemand Quote, het bericht ervan opvragen
	if($_GET['quoteid'] != '') {
		$dirty_html_quoteid = $_GET['quoteid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$quoteid = $purifier->purify($dirty_html_quoteid);

		$st = $db->prepare("SELECT bericht FROM forum_berichten WHERE id = :quoteid");
		$st->bindParam(':quoteid', $quoteid, PDO::PARAM_INT);
		$st->execute();
		$quote = $st->fetch();

	}
	elseif($_GET['editid'] != '') {
		$dirty_html_editid = $_GET['editid'];
		require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$editid = $purifier->purify($dirty_html_editid);
		
		$st = $db->prepare("SELECT bericht FROM forum_berichten WHERE id = :editid");
		$st->bindParam(':editid', $editid, PDO::PARAM_INT);
		$st->execute();
		$edit = $st->fetch();
	}
	
	$dirty_html_thread = mysql_escape_string($_GET['thread']);
	require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$thread = $purifier->purify($dirty_html_thread);
	
	$dirty_html_category = $_GET['category'];
	require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$category = $purifier->purify($dirty_html_category);


	$st = $db->prepare("SELECT forum_berichten.*, gebruikers.username
						 FROM forum_berichten
						 INNER JOIN gebruikers
						 ON forum_berichten.user_id = gebruikers.user_id
						 WHERE forum_berichten.categorie_id = :category
						 AND forum_berichten.topic_id = :thread
						 ORDER BY forum_berichten.datum ASC LIMIT :pagina, :maxi");
	$st->bindParam(':category', $category, PDO::PARAM_INT);
	$st->bindParam(':thread', $thread, PDO::PARAM_INT);
	$st->bindParam(':pagina', $pagina, PDO::PARAM_INT);
	$st->bindParam(':maxi', $max, PDO::PARAM_INT);
	$st->execute();
	$query = $st->fetchAll();

	
	if(empty($query)){
		echo $txt['no_messages'].'<HR>';
	}
	else{
	$number = 1;
		foreach($query as $info){
			#Datum-tijd goed
			$datum = explode("-", $info['datum']);
			$tijd = explode(" ", $datum[2]);
			$datum = $tijd[0]."-".$datum[1]."-".$datum[0].",&nbsp;".$tijd[1];
			$datum_finished = substr_replace($datum ,"",-3);
			
			#Enters in de textarea ook weergeven als een enter
			$tekst = nl2br($info['bericht']);
			#Van [player]Skank[/player] een link maken naar de player
			$tekst = eregi_replace("\[player\]([^\[]+)\[/player\]","<a class=\"atag\" href=\"?page=profile&player=\\1\">\\1</a>",$tekst);
			#Van [icon]charizard[/icon] plaatje maken naar de animatie van de pokemon
			$tekst = eregi_replace("\[icon\]([^\[]+)\[/icon\]","<img src=\"images/pokemon/icon/\\1.gif\" border=\"0\">",$tekst);
			$tekst = eregi_replace("\[icon_shiny\]([^\[]+)\[/icon_shiny\]","<img src=\"images/shiny/icon/\\1.gif\" border=\"0\">",$tekst);
			#Van [back]charizard[/back] plaatje maken naar de rug van de pokemon
			$tekst = eregi_replace("\[back\]([^\[]+)\[/back\]","<img src=\"images/pokemon/back/\\1.png\" border=\"0\">",$tekst);
			$tekst = eregi_replace("\[back_shiny\]([^\[]+)\[/back_shiny\]","<img src=\"images/shiny/back/\\1.png\" border=\"0\">",$tekst);
			#Van [back]charizard[/back] plaatje maken naar de pokemon
			$tekst = eregi_replace("\[pokemon\]([^\[]+)\[/pokemon\]","<img src=\"images/pokemon/\\1.png\" border=\"0\">",$tekst);
			$tekst = eregi_replace("\[shiny\]([^\[]+)\[/shiny\]","<img src=\"images/shiny/\\1.png\" border=\"0\">",$tekst);
			#Plaatje maken
    		$tekst = eregi_replace("\\[img]([^\\[]*)\\[/img\\]","<img src=\"\\1\" border=\"0\" OnLoad=\"if(this.width > 580) {this.width=580}\">",$tekst);
			#Tekst dik gedrukt maken
			$tekst = eregi_replace("\[b\]","<strong>",$tekst);
			$tekst = eregi_replace("\[/b\]","</strong>",$tekst);
			#Tekst onderstreept maken
			$tekst = eregi_replace("\[u\]","<u>",$tekst);
			$tekst = eregi_replace("\[/u\]","</u>",$tekst);
			#Tekst Schuin gedrukt maken
			$tekst = eregi_replace("\[i\]","<em>",$tekst);
			$tekst = eregi_replace("\[/i\]","</em>",$tekst);
			#Tekst centreren
			$tekst = eregi_replace("\[center\]","<center>",$tekst);
			$tekst = eregi_replace("\[/center\]","</center>",$tekst);
			#Lopend balkje in beeld
			$tekst = eregi_replace("\[marquee\]([^\[]+)\[/marquee\]","<marquee>\\1</marquee>",$tekst);
			#kleur veranderen
			$tekst = eregi_replace("\[color=([^\[]+)\]([^\[]+)\[/color\]","<font color=\\1>\\2</font>",$tekst);
			#Quote
			$tekst = eregi_replace("\[quote\]","<div class='quote'>",$tekst);
			$tekst = eregi_replace("\[/quote\]","</div>",$tekst);
																
			#Van plaatjes invoeren
			# Pad naar de afbeeldingen (inclusief slash aan het einde)
			$pad = "images/emoticons/";
			# UBB code => Bestandsnaam
			$smiley = array(
			  ":)" => "001.png",
			  ":D" => "002.png",
			  ":P" => "104.png",
			  ";)" => "003.png",
			  ":S" => "009.png",
			  ":O" => "004.png",
			  "xD" => "107.png",
			  "8-)" => "050.png",
			  "<o)" => "075.png",
			  "(K)" => "028.png",
			  "(BOO)" => "096.png",
			  "(J)" => "086.png",
			  "(V)" => "087.png",
			  ":8)" => "088.png",
			  ":@" => "099.png",
			  ":$" => "008.png",
			  ":-#" => "048.png",
			  ":(" => "010.png",
			  ":'(" => "011.png",
			  ":|" => "012.png",
			  "(H)" => "006.png",
			  "(A)" => "014.png",
			  "|-)" => "078.png",
			  "(T)" => "034.png",
			  "+o(" => "053.png",
			  "(L)" => "015.png",
			  ":[" => "043.png", 
			  "(G)" => "gold.png",
			  "(S)" => "silver.png",
			  ":'|" => "093.png",
			  "(F)" => "025.png",
			  "(Y)" => "041.png",
			  "(N)" => "042.png"
			);
			foreach($smiley as $bb => $img)
			  $tekst = preg_replace("#".preg_quote($bb,'#')."#i","<img src='".$pad.$img."' width='19' height='19' alt='".$bb."' />",$tekst);
	  
			echo '<table width="660" cellpadding="0" cellspacing="0">
					<tr>
						<td class="top_first_td" width="220"><img src="images/icons/man.png" style="margin-bottom:-3px;"> <a class="atag" href="?page=profile&player='.$info['username'].'">'.$info['username'].'</a></td>
						<td class="top_td" width="220"><center><img src="images/icons/datum.png" style="margin-bottom:-3px;"> '.$datum_finished.'</center></td>
						<td class="top_td" width="220">
							<div style="float: right; padding-right:10px;">';
						if(($_SESSION['naam'] != '') && ($prop['status'] == 1)){
							echo '<a href="?page=forum-messages&category='.$_GET['category'].'&thread='.$_GET['thread'].'&subpage='.$_GET['subpage'].'&quoteid='.$info['id'].'#send"><img src="images/icons/comment.png" title="'.$txt['quote_this_message'].'" style="margin-bottom:-3px;"></a>';
						}
						if($gebruiker['admin'] >= 1){
							echo ' <a href="?page=forum-messages&category='.$_GET['category'].'&thread='.$_GET['thread'].'&subpage='.$_GET['subpage'].'&editid='.$info['id'].'#send"><img src="images/icons/comment_edit.png" title="'.$txt['edit_this_message'].'"></a> 
							<a href="?page=forum-messages&category='.$_GET['category'].'&thread='.$_GET['thread'].'&subpage='.$_GET['subpage'].'&deleteid='.$info['id'].'"><img src="images/icons/comment_delete.png" title="'.$txt['delete_this_message'].'" style="margin-bottom:-3px;"></a>';
						}
						echo '</div></td>
				  	</tr>
					<tr>
						<td class="normal_first_td" style="padding-right:10px;" colspan="3">'.utf8_decode($tekst).'</td>
					</tr>
				  	</table>
				  	<HR>';
		}
		$number++;
	}
	
#Paginasysteem
    $links = false;
    $rechts = false;
    echo '<table width="660">
			<tr>
				<td><center><br /><div class="sabrosus">';
    if($subpage == 1)
      echo '<span class="disabled"> &lt; </span>';
    else{
      $back = $subpage-1;
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$back.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'"> &lt; </a>';
    }
    for($i = 1; $i <= $aantal_paginas; $i++) { 
      if((2 >= $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif((2 >= $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
      elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
        echo '<span class="current">'.$i.'</span>';
      elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
      else{
        $max = $subpage+3;
        $min = $subpage-3;  
        if($subpage == $i)
          echo '<span class="current">'.$i.'</span>';
        elseif(($min < $i) && ($max > $i))
        	echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$i.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'">'.$i.'</a>';
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
      echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&subpage='.$next.'&category='.$_GET['category'].'&thread='.$_GET['thread'].'"> &gt; </a>';
    }
    echo "</div></center></td>
		</tr>
	</table>
	<HR>";
#Einde paginasysteem

if($_SESSION['id'] == '')
	echo $txt['first_login'];
elseif(($prop['status'] == 0) && ($_GET['editid'] == '')){
	echo $txt['topic_closed_no_reply'];
}
else{ ?>
        
<form method="post" action="#send" name="bericht">
<div id="send">
<?php if($error != '') echo $error; ?>
<div style="padding-bottom:10px;"><label for="message"><img src="images/icons/page_add.png" width="16" height="16" /> <strong><?php echo $txt['add_message']; ?></strong></label></div>

<link href="includes/summernote/bootstrap.css" rel="stylesheet">
<link href="includes/summernote/summernote.css" rel="stylesheet">
<script>
$(document).ready(function() {
    $('#summernote').summernote({
        theme: 'yeti',
        lang: "<?=GLOBALDEF_EDITORLANGUAGE?>",
        callbacks : {
        onImageUpload: function(image) {
            uploadImage(image[0]);
        }
    },
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video', 'hr']],
        ['view', ['fullscreen']]
      ]
    });
    function uploadImage(image) {
    var data = new FormData();
    data.append("image",image);
    $.ajax ({
        data: data,
        type: "POST",
        url: "upload-image.php",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
           /* $('.summernote').summernote('insertImage', url);*/
            $('#summernote').summernote('insertImage', url, function ($image) {
              $image.css('width', $image.width() / 3);
              $image.attr('data-filename', 'retriever');
            });
			//console.log(url);
            },
            error: function(data) {
                console.log(data);
                }
        });
    }
});
</script>

<table width="100%">
    <tr>
      <td><textarea id="summernote" class="text_area" rows="12" name="tekst" id="message"><?php if(!empty($_POST['tekst'])) echo $_POST['tekst']; elseif($_GET['quoteid'] != '') echo '[quote]'.$quote['bericht'].'[/quote]'; elseif($_GET['editid'] != '') echo $edit['bericht']; ?></textarea></td>
    </tr>
	<tr>
		<td>
			<center>
				<?
				foreach (showEmoticons() as $emoticon) {
					echo $emoticon['symbols'] . " " . $emoticon['icon'] . " ";
				}
				?>
			</center><br/>
		</td>
	</tr>
    <tr>
      <td style="padding-top:5px;"><input type="submit" value="<?php echo $txt['button']; ?>" name="submit" class="button"/></td>
    </tr>
  </table>
  </div>
</form>
<?php } ?>