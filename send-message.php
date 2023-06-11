<?php 
	#Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");

	$page = 'send-message';
	#Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');

        #Als er een ontvanger in de link zit deze gebruiken.
        if(isset($_GET['player'])) $ontvanger = $_GET['player'];
        
        #Als er op de stuur bericht knop word gedrukt, dit uitvoeren
        if(isset($_POST['bericht'])){
			$ontvanger = $_POST['ontvanger'];
			#Gegevens van ontvanger laden
			$ontvangergeg = mysql_fetch_array(mysql_query("SELECT `user_id`, `admin`, `premiumaccount`, `blocklist` FROM `gebruikers` WHERE `username`='".$_POST['ontvanger']."'"));
  	
			$hoeveelberichten = mysql_num_rows(mysql_query("SELECT `id` FROM `berichten` WHERE `ontvanger_id`='".$ontvangergeg['user_id']."'"));
    			
			#Hoeveel in inbox?
			$maxberichten = 30;
			if($ontvangergeg['admin'] == '1')	$maxberichten = 1000;
			elseif($ontvangergeg['admin'] == '2')	$maxberichten = 1100;
			elseif($ontvangergeg['premiumaccount'] >= 1) $maxberichten = 60;

				
	if(empty($_POST['ontvanger'])) echo '<div class="red">'.$txt['alert_no_receiver'].'</div>';
	elseif($hoeveelberichten >= $maxberichten) echo '<div class="red">'.$txt['alert_inbox_full'].'</div>';
	elseif(strtolower($_POST['ontvanger']) == strtolower($_SESSION['naam'])) echo '<div class="red">'.$txt['alert_message_to_yourself'].'</div>';
	elseif(empty($ontvangergeg['user_id'])) echo '<div class="red">'.$txt['alert_username_dont_exist'].'</div>';
	elseif(getBans($_POST['ontvanger'],$_SESSION['naam'],"communicatie") === true) echo '<div class="red">'.$txt['alert_communication_ban'].'</div>';
	elseif(empty($_POST['onderwerp'])) echo '<div class="red">'.$txt['alert_no_subject'].'</div>';
	elseif(empty($_POST['tekst'])) echo '<div class="red">'.$txt['alert_no_message'].'</div>';
	else{
	
	$dirty_html_tekst = $_POST['tekst'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
    
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $tekst = $purifier->purify($dirty_html_tekst);
          
    $dirty_html_subject = $_POST['onderwerp'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
    
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $subject = $purifier->purify($dirty_html_subject);
	
		mysql_query("INSERT INTO `berichten` (`datum`, `ontvanger_id`, `afzender_id`, `bericht`, `onderwerp`) 
				  VALUES (NOW(), '".$ontvangergeg['user_id']."', '".$_SESSION['id']."', '".$tekst."', '".$subject."')");
		echo '<div class="green">'.$txt['success_send_message'].'</div>';
	}      
}
?>

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

<form method="post" name="bericht">
<table width="100%">
    <tr>
      <td width="120"><label for="ontvanger"><?php echo $txt['name_receiver']; ?></label></td>
      <td><input type="text"  class="text_long" name="ontvanger" id="ontvanger" value="<? if(isset($_POST['ontvanger'])) echo $_POST['ontvanger']; ?>" maxlength="10" /></td>
    </tr>
    <tr>
      <td width="120"><label for="onderwerp"><?php echo $txt['subject']; ?></label></td>
      <td><input type="text" class="text_long" name="onderwerp" id="onderwerp" value="<? if(isset($_POST['onderwerp'])) echo $_POST['onderwerp']; ?>" maxlength="50"/></td>
    </tr>
  </table>  
  <table width="100%">
    <tr>
      <td><textarea rows="12" id="summernote" name="tekst" style="width: 100%;"><?php echo $_POST['tekst']; ?></textarea></td>
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
      <td><button type="submit"  name="bericht" class="button pull-right"><?php echo $txt['button']; ?></button></td>
    </tr>
  </table>
</form>