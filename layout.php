<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'layout';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if (isset($_POST['profiel'])) {

    $store_dirty = $_POST['store'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $store = $purifier->purify($store_dirty);

    $profilestore_dirty = $_POST['profilestore'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $profilestore = $purifier->purify($profilestore_dirty);
    
    if($_POST['activate']){
        $q = "UPDATE `gebruikers` SET `hasStore`=1 WHERE `user_id`=:user_id";
        $st = $db->prepare($q);
        $st->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $start = $st->execute();
    }else{
        $q = "UPDATE `gebruikers` SET `hasStore`=0 WHERE `user_id`=:user_id";
        $st = $db->prepare($q);
        $st->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $start = $st->execute();
    
    }

    $q = "UPDATE `gebruikers` SET `store`=:store,`profilestore`=:profilestore WHERE `user_id`=:user_id";
    $st = $db->prepare($q);
    $st->bindParam(':store', $store, PDO::PARAM_STR);
    $st->bindParam(':profilestore', $profilestore, PDO::PARAM_STR);
    $st->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $start = $st->execute();

    $storetekst = '<div class="green">Je store instellingen zijn opgeslagen.</div>';
}

    $textSQL = $db->prepare("SELECT `store`,`profilestore`,`hasStore` FROM `gebruikers` WHERE `user_id`=:uid");
    $textSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
    $textSQL->execute();

    $text = $textSQL->fetch(PDO::FETCH_ASSOC);
    
    if($text['hasStore']){
        $checked = 'checked';
    }else{
        $checked = '';
    }

?>

<link href="includes/summernote/bootstrap.css" rel="stylesheet">
<link href="includes/summernote/summernote.css" rel="stylesheet">
<script>
$(document).ready(function() {
    $('#store').summernote({
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
    $('#profilestore').summernote({
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

<form method="post">
<center>
    <p>
    <h2>Open mijn store:</h2>
    <label class="switch">
      <input type="checkbox" name="activate" <?=$checked?>>
      <div class="switchslider round"></div>
    </label>
    </p>
</center>

<?php if(isset($_POST['store'])) echo $storetekst; ?>
    <center>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                <h2>Store tekst:</h2>
                <textarea id="store" rows="12" name="store"
                              style="width: 100%;"><? echo $text['store']; ?></textarea></td>
            </tr>
            <tr>
                <td>
                <h2>Profiel tekst:</h2>
                <textarea id="profilestore" rows="12" name="profilestore"
                              style="width: 100%;"><? echo $text['profilestore']; ?></textarea></td>
            </tr>
            <tr>
                <td height="25"><br/>
                    <button type="submit" name="profiel" class="button pull-right">Opslaan</button>
                </td>
            </tr>
        </table>
    </center>
</form>   