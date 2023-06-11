<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 3){
  header('location: index.php?page=home');
}

if(isset($_POST['change'])){

    if(!empty($_POST['titel']) && !empty($_POST['text'])) {
        $addNews = "INSERT INTO nieuws (titel_" . GLOBALDEF_LANGUAGE . ",text_" . GLOBALDEF_LANGUAGE . ",datum) VALUES (:titel,:text,NOW())";
        $addNews = $db->prepare($addNews);
        $addNews->bindParam(':titel', $_POST['titel'], PDO::PARAM_STR);
        $addNews->bindParam(':text', $_POST['text'], PDO::PARAM_STR);
        $addNews->execute();

        echo '<div class="green"><img src="images/icons/green.png" width="16" height="16" /> A new news post has been created!</div>';
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

<form method="post">

<strong>Title</strong>
<br/>
<center>
<input type="text" id="titel" name="titel" class="text_long" style="float:none; width:86%;background-color: white!important;border: 0px;" maxlength="200">
</center>
<br/>
<br/>
<strong>Message</strong>
<div style="padding: 6px 0px 6px 0px;" align="center">
	<table width="600" cellpadding="0" cellspacing="0">
    	<tr>
            <td><textarea style="width:100%;" id="summernote" rows="15" name="text"></textarea></td>
        </tr>
        	<td><div style="padding-top:10px;"><input type="submit" name="change" value="Add" class="button" /></div></td>
        </tr>
    </table> 
</div>        

</form>
