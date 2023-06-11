<?php
if(isset($_GET['reageren'])){

    if(isset($_POST['bericht'])){
        if(!empty($_SESSION['id'])) {
            $dirty_html_text = $_POST['content'];
            require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $text = $purifier->purify($dirty_html_text);

            if ($text) {
                if (isset($_GET['uid'])) {
                    $q = "INSERT INTO `nieuws_reacties`(`gebruiker`,`nieuws_id`,`bericht`)
                                  values(:userid,:nieuws_id,:bericht)";
                    $st = $db->prepare($q);
                    $st->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
                    $st->bindParam(':nieuws_id', $_GET['uid'], PDO::PARAM_INT);
                    $st->bindParam(':bericht', $text, PDO::PARAM_STR);
                    $start = $st->execute();

                    echo showAlert('green', 'Je reactie is geplaatst.') . '<br/>';
                    ?>
                    <script>
                        setTimeout(displayNone, 3000);
                        function displayNone() {
                            document.getElementById("notification").style.display = "none";
                        }
                    </script>
                    <?
                }
            }
        }
    }

    if(isset($_GET['uid'])) {
        $getNews = $db->prepare("SELECT * FROM nieuws WHERE id = :uid");
        $getNews->bindValue(':uid', $_GET['uid'], PDO::PARAM_INT);
        $getNews->execute();
        $getNews = $getNews->fetch();
    }

    ?>
    <div class='newstitle wordwrap'><?= $getNews['datum'] ?>: <?= $getNews['titel_'.GLOBALDEF_LANGUAGE] ?></div>
    <div class='newscontent'><?= replaceEmoticons($getNews['text_'.GLOBALDEF_LANGUAGE]) ?></div>
    <div class='newsfooter' style="margin-bottom: 5px;">
        <a href="?page=home"><< Nieuws</a>
    </div><br/>
    <?

    $getResponses = $db->prepare("SELECT * FROM nieuws_reacties WHERE nieuws_id = :uid ORDER BY id");
    $getResponses->bindValue(':uid', $_GET['uid'], PDO::PARAM_INT);
    $getResponses->execute();
    $getResponses = $getResponses->fetchAll();

    foreach ($getResponses as $response) {
        ?>
        <hr>
        <form method="post" action="index.php?page=home">
            <div class='newstitle wordwrap'><?= getUsername($response['gebruiker']) ?></div>
            <div class='newscontent'><?= replaceEmoticons($response['bericht']) ?></div>
            <div class='newsfooter' style="margin-bottom: 5px;"><?= $response['datum'] ?></div>
        </form>
        <?

    }
    if(!empty($_SESSION['id'])) {
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
        <form method="post" action="?page=news&reageren&uid=<?= $_GET['uid'] ?>">
            <table width="100%">
                <tr>
                    <td><textarea rows="12" id="summernote" name="content" style="width: 100%;"></textarea></td>
                </tr>
                <tr>
                    <td>
                        <center>
                            <?
                            foreach (showEmoticons() as $emoticon) {
                                echo $emoticon['symbols'] . " " . $emoticon['icon'] . " ";
                            }
                            ?>
                        </center>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="bericht" class="button">Versturen</button>
                    </td>
                </tr>
            </table>
        </form>

        <?
    }

} else {
    if (isset($_POST['like'])) {

        $checkLikes = $db->prepare("SELECT id FROM nieuws_likes WHERE gebruiker = :userid AND nieuws_id = :uid");
        $checkLikes->bindValue(':userid', $_SESSION['id'], PDO::PARAM_INT);
        $checkLikes->bindValue(':uid', $_POST['uid'], PDO::PARAM_INT);
        $checkLikes->execute();
        $checkLike = $checkLikes->fetch();

        if (!$checkLike) {
            $q = "INSERT INTO `nieuws_likes`(`gebruiker`,`nieuws_id`)
                                  values(:userid,:uid)";
            $st = $db->prepare($q);
            $st->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
            $st->bindParam(':uid', $_POST['uid'], PDO::PARAM_INT);
            $start = $st->execute();
        }
    }

    if (!(isset($pagenum))) {
        $pagenum = 1;
    }

    if (empty($_GET['subpage'])) {
        $subpage = 1;
    } else {
        $subpage = $_GET['subpage'];
    }

    $textNl = $db->query("SELECT text_".GLOBALDEF_LANGUAGE." FROM nieuws");
    $aantal_rows = $textNl->rowCount();

    #Max aantal berichten per pagina
    $max = 2;
    $aantal_paginas = ceil($aantal_rows / $max);
    if ($aantal_paginas == 0) $aantal_paginas = 1;
    $pagina = $subpage * $max - $max;

    $sql = $db->query("SELECT id,titel_".GLOBALDEF_LANGUAGE.",text_".GLOBALDEF_LANGUAGE.",DATE_FORMAT(`datum`,'%d-%m-%Y') AS `datum`,UNIX_TIMESTAMP(datum) AS DATE FROM nieuws
								ORDER BY DATE DESC
								LIMIT " . $pagina . ", " . $max . "");


    for ($j = 1; $select = $sql->fetch(PDO::FETCH_ASSOC); $j++) {

        //check how many comments a news message has
        $nieuws_reacties = $db->prepare("SELECT id FROM nieuws_reacties WHERE nieuws_id = :nieuws_id");
        $nieuws_reacties->bindParam(':nieuws_id', $select["id"], PDO::PARAM_INT);
        $nieuws_reacties->execute();
        $nieuws_reacties = $nieuws_reacties->rowCount();
        if ($nieuws_reacties == null) {
            $nieuws_reacties = '0';
        }
        //check how many likes a news message has
        $nieuws_likes = $db->prepare("SELECT id FROM nieuws_likes WHERE nieuws_id = :newsid");
        $nieuws_likes->bindParam(':newsid', $select['id'], PDO::PARAM_INT);
        $nieuws_likes->execute();
        $nieuws_likes = $nieuws_likes->rowCount();
        if ($nieuws_likes == null) {
            $nieuws_likes = '0';
        }
        
        //check if the user liked the news message
        $nieuws_liked = $db->prepare("SELECT id FROM nieuws_likes WHERE nieuws_id = :newsid AND gebruiker=:user_id");
        $nieuws_liked->bindParam(':newsid', $select['id'], PDO::PARAM_INT);
        $nieuws_liked->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $nieuws_liked->execute();
        $nieuws_liked = $nieuws_liked->rowCount();
        if($nieuws_liked){
            $nieuws_liked = "-webkit-filter: drop-shadow(1px 1px 0 #E8ADAA)drop-shadow(-1px 1px 0 #E8ADAA)drop-shadow(1px -1px 0 #E8ADAA)drop-shadow(-1px -1px 0 #E8ADAA);
            filter: drop-shadow(1px 1px 0 #E8ADAA) drop-shadow(-1px 1px 0 #E8ADAA) drop-shadow(1px -1px 0 #E8ADAA) drop-shadow(-1px -1px 0 #E8ADAA);";
            $text = 'Liked';
        }else{
            $nieuws_liked = '';
            $text = 'Like';
        }
        
        ?>
        <hr>
        <form method="post" action="index.php?page=home">
            <div class='newstitle wordwrap'><?= $select['datum'] ?>: <?= $select['titel_'.GLOBALDEF_LANGUAGE] ?></div>
            <div class='newscontent'><?= replaceEmoticons($select['text_'.GLOBALDEF_LANGUAGE]) ?></div>
            <div class='newsfooter' style="margin-bottom: 5px;">
                <a href='?page=news&reageren&uid=<?= $select['id'] ?>'>Reageren (<?= $nieuws_reacties ?>)</a>
                <?if(!empty($_SESSION['id'])){?>
                <input type="hidden" name="uid" value="<?= $select['id'] ?>">
                <button type="submit" name="like" style="border: 0; background: transparent;cursor: pointer;"
                        class="pull-right">
                    <img src="images/shoutbox_icons/emoticon_heart.png" width="16" height="16" alt="Like" title="Like"
                         style="margin-bottom: -3px;<?=$nieuws_liked?>"/>
                    <a><?=$text?> (<?= $nieuws_likes ?>)</a>
                </button>
                <?}?>
            </div>
        </form>
        <?

    }

#Pagina systeem
    $links = false;
    $rechts = false;
    echo '<center><br /><div class="pagination">';
    if ($subpage == '1') {
        echo '<span class="disabled"> &lt; </span>';
    } else {
        $back = $subpage - 1;
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&subpage=' . $back . '"> &lt; </a>';
    }
    for ($i = 1; $i <= $aantal_paginas; $i++) {

        if ((2 >= $i) && ($subpage == $i)) {
            echo '<span class="current">&nbsp;' . $i . '&nbsp;</span>';
        } elseif ((2 >= $i) && ($subpage != $i)) {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&subpage=' . $i . '">&nbsp;' . $i . '&nbsp;</a>';
        } elseif (($aantal_paginas - 2 < $i) && ($subpage == $i)) {
            echo '<span class="current">&nbsp;' . $i . '&nbsp;</span>';
        } elseif (($aantal_paginas - 2 < $i) && ($subpage != $i)) {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&subpage=' . $i . '">&nbsp;' . $i . '&nbsp;</a>';
        } else {
            $max = $subpage + 3;
            $min = $subpage - 3;
            if ($subpage == $i) {
                echo '<span class="current">&nbsp;' . $i . '&nbsp;</span>';
            } elseif (($min < $i) && ($max > $i)) {
                echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&subpage=' . $i . '">&nbsp;' . $i . '&nbsp;</a>';
            } else {
                if ($i < $subpage) {
                    if (!$links) {
                        echo '<span class="disabled">...</span>';
                        $links = True;
                    }
                } else {
                    if (!$rechts) {
                        echo '<span class="disabled">...</span>';
                        $rechts = True;
                    }
                }

            }
        }
    }
    if ($aantal_paginas == $subpage) {
        echo '<span class="disabled"> &gt; </span>';
    } else {
        $next = $subpage + 1;
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&subpage=' . $next . '"> &gt; </a>';
    }
    echo "</div></center>";

}