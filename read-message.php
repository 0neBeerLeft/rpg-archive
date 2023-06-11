<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'read-message';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if (isset($_GET['code'])) {
    #Code opvragen en decoderen
    $link = base64_decode($_GET['code']);
    #Code splitten, zodat informatie duidelijk word
    list ($id, $ontvanger_id, $afzender_id, $onderwerp) = split('[/]', $link);

    #Kijken als de informatie nog wel klopt.
    if (mysql_num_rows(mysql_query("SELECT `id` FROM `berichten` WHERE `id`='" . $id . "' AND `onderwerp`='" . $onderwerp . "' AND `afzender_id`='" . $afzender_id . "' AND `ontvanger_id`='" . $ontvanger_id . "'")) == 0) {
        echo '<div class="red">' . $txt['alert_link_incorrect'] . '</div>';
    } elseif ($ontvanger_id != $_SESSION['id']) {
        echo '<div class="red">' . $txt['alert_not_your_message'] . '</div>';
    } else {
        #gegevens van het bericht laden
        $bericht = mysql_fetch_assoc(mysql_query("SELECT berichten.*, gebruikers.user_id, gebruikers.username, gebruikers.admin, gebruikers.premiumaccount, gebruikers.blocklist
						   FROM berichten
						   LEFT JOIN gebruikers
						   ON berichten.afzender_id = gebruikers.user_id
						   WHERE `id`='" . $id . "' AND `onderwerp`='" . $onderwerp . "' AND `afzender_id`='" . $afzender_id . "' AND `ontvanger_id`='" . $ontvanger_id . "'"));
        #Opslaan dat het bericht is gelezen
        mysql_query("UPDATE `berichten` SET `gelezen`='1' WHERE `id`='" . $bericht['id'] . "'");
        #code maken die word mee gegeven
        $codekort = $bericht['username'] . "/" . $bericht['onderwerp'];
        #code encoderen
        $linkkort = base64_encode($codekort);
        #premium things:
        $premstar = '';

        if ($bericht['premiumaccount'] > 0) {
            $premstar = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
        }

        #Als er op de stuur bericht knop word gedrukt, dit uitvoeren
        if (isset($_POST['bericht'])) {
            #Gegevens van ontvanger laden
            $ontvangergeg = mysql_fetch_assoc(mysql_query("SELECT `user_id`, `username`, `admin`, `premiumaccount`, `blocklist` FROM `gebruikers` WHERE `user_id`='" . $afzender_id . "'"));

            $hoeveelberichten = mysql_num_rows(mysql_query("SELECT `id` FROM `berichten` WHERE `ontvanger_id`='" . $ontvangergeg['user_id'] . "'"));

            # Hoeveel in inbox?
            $maxberichten = 30;
            if ($bericht['admin'] == '1') $maxberichten = 1000;
            elseif ($bericht['admin'] == '2') $maxberichten = 1100;
            elseif ($bericht['premiumaccount'] >= 1) $maxberichten = 60;

            if ($hoeveelberichten >= $maxberichten)
                $alert = '<div class="red">' . $txt['alert_inbox_full'] . '</div>';
            elseif (empty($_POST['tekst']))
                $alert = '<div class="red">' . $txt['alert_no_message'] . '</div>';
            else {

                $dirty_html_tekst = $_POST['tekst'];
                require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $tekst = $purifier->purify($dirty_html_tekst);

                $dirty_html_subject = $onderwerp;
                require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $subject = $purifier->purify($dirty_html_subject);

                $check = substr($subject, 0, 2);
                if ($check != "RE") {
                    $subject = "RE: " . $subject;
                }

                mysql_query("INSERT INTO `berichten` (`datum`, `ontvanger_id`, `afzender_id`, `bericht`, `onderwerp`) 
              VALUES (NOW(), '" . $afzender_id . "', '" . $_SESSION['id'] . "', '" . $tekst . "', '" . $subject . "')");
                $alert = '<div class="green">' . $txt['success_send_message'] . ' ' . $bericht['username'] . '.</div>';
            }
        }
        ?>

        <link href="includes/summernote/bootstrap.css" rel="stylesheet">
        <link href="includes/summernote/summernote.css" rel="stylesheet">
        <script>
            $(document).ready(function () {
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
        <table width="100%" border="0">
            <tr>
                <td width="360"><strong><?php echo $txt['from_player']; ?></strong> <a
                        href="?page=profile&player=<?php echo $bericht['username']; ?>"><?php echo $bericht['username'] . $premstar; ?></a><span
                        style="padding-left:30px;"><strong><?php echo $txt['subject']; ?></strong> <?php echo $onderwerp; ?></span>
                </td>
                <td width="300">
                    <div align="right"><a href="?page=inbox"><img src="images/icons/berichtinbox.png" width="16"
                                                                  height="16" border="0"/> <?php echo $txt['inbox']; ?>
                        </a> | <a href="?page=blocklist&player=<?php echo $bericht['username']; ?>"><img
                                src="images/icons/blokkeer.png" width="16" height="16"
                                border="0"> <?php echo $txt['block'] . ' ' . $bericht['username']; ?></a></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <HR/>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 20px 10px 20px 10px;"><?php echo replaceEmoticons($bericht['bericht']); ?></td>
            </tr>
        </table>
        <HR/>
        <?php if ($alert != '') echo $alert; ?>
        <div style="padding-bottom:10px;"><label for="input"><img src="images/icons/user_comment.png" width="16"
                                                                  height="16" border="0"/>
                <strong><?php echo $txt['respond']; ?></strong></label></div>
        <?php /*echo $txt['link_text_effects']; */
        ?>
        <form method="post" name="bericht">
            <table width="660" border="0">
                <tr>
                    <td><textarea rows="12" style="width:100%;" id="summernote" name="tekst"
                                  id="input"><?php echo $_POST['tekst']; ?></textarea></td>
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
                    <td style="padding-top:5px;"><input type="submit" value="<?php echo $txt['button']; ?>"
                                                        name="bericht" class="button"/></td>
                </tr>
            </table>
        </form>
        <?php
    }
} else {
    //Terug sturen naar de inbox
    header("Location: index.php?page=inbox");
}
?>