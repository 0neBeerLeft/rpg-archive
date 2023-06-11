<?PHP

#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

?>
<link href="includes/summernote/bootstrap.css" rel="stylesheet">
<link href="includes/summernote/summernote.css" rel="stylesheet">
<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            theme: 'yeti',
            lang: "<?=GLOBALDEF_EDITORLANGUAGE?>",
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
    });
</script>
Als je iets niet wil vergeten kan je het in je notepad zetten.<br/><br/>
<form method="post">

    <?PHP
    $SqlCount = mysql_query("SELECT id FROM notes WHERE `user_id`='" . $_SESSION['id'] . "'");
    if (isset($_POST['update'])) {
    
    $dirty_html_tekst = $_POST['notitie'];
    require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';
    
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $tekst = $purifier->purify($dirty_html_tekst);
    
        if ($tekst == '') {
            echo showAlert('red','Je hebt niks ingevuld.');
        } else {
            if (mysql_num_rows($SqlCount) >= 1) {
                // UPDATE
                mysql_query("UPDATE notes SET text='" . mysql_real_escape_string($tekst) . "', postdate=NOW() WHERE `user_id`='" . $_SESSION['id'] . "'");
                echo showAlert('green','Kladblok is vernieuwd!');
            } else {
                // INSERT
                mysql_query("INSERT INTO notes(user_id,text,postdate) VALUES('" . $_SESSION['id'] . "','" . mysql_real_escape_string($tekst) . "',NOW())");
                echo showAlert('green','Kladblok is vernieuwd!');
            }
        }
    }

    $qNotes = mysql_query("SELECT * FROM notes WHERE `user_id`='" . $_SESSION['id'] . "'");
    if (mysql_num_rows($qNotes) > 0) {
        $sNotes = mysql_fetch_assoc($qNotes);

    } else {
        $sNotes['text'] = '';
    }
    ?>
    <tr><td class=inhoud>
        <textarea name="notitie" id="summernote" class="text_area"><?=$sNotes['text']?></textarea><br><br>
        <button type="submit" name="update" class="button">Updaten</button>
    </td></tr>
    

</form>