<?php
// Allowed extentions.
$allowedExts = array("gif", "jpeg", "jpg", "png");

// Get filename.
$temp = explode(".", $_FILES["image"]["name"]);

// Get extension.
$extension = end($temp);

if ($_FILES['image']['name']) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

    if ((($mime == "image/gif")
            || ($mime == "image/jpeg")
            || ($mime == "image/pjpeg")
            || ($mime == "image/x-png")
            || ($mime == "image/png"))
        && in_array(strtolower($extension), $allowedExts)
    ) {
        if (!$_FILES['image']['error']) {
            $name = md5(rand(100, 200));
            $ext = explode('.', $_FILES['image']['name']);
            $filename = $name . '.' . $ext[1];
            $destination = './uploads/' . $filename; //change this directory
            $location = $_FILES["image"]["tmp_name"];
            move_uploaded_file($location, $destination);
            echo ''.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/uploads/' . $filename;//change this URL
            
        } else {
            echo $message = 'Oeps, Er is iets mis gegaan met het uploaden.'; // $_FILES['file']['error']
        }
    } else {
        echo $message = 'Oeps, Er is iets mis gegaan met het uploaden.'; // $_FILES['file']['error']
    }
} else {
    echo $message = 'Bestandstype onjuist.';
}