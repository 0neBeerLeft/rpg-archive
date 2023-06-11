<?
// index.php
function generate_resized_image(){
    $max_dimension = 100; // Max new width or height, can not exceed this value.
    $dir = "uploads/"; // Directory to save resized image. (Include a trailing slash - /)
    // Collect the post variables.
    $postvars = array(
        "image"    => trim($_FILES["image"]["name"]),
        "image_tmp"    => $_FILES["image"]["tmp_name"],
        "image_size"    => (int)$_FILES["image"]["size"],
        "image_max_width"    => (int)660, "image_max_height"   => (int)370
    );
    // Array of valid extensions.
    $valid_exts = array("jpg","jpeg","gif","png");
    // Select the extension from the file.
    $ext = end(explode(".",strtolower(trim($_FILES["image"]["name"]))));
    // Check not larger than 175kb.
    if($postvars["image_size"] <= 2097152){
        // Check is valid extension.
        if(in_array($ext,$valid_exts)){
            if($ext == "jpg" || $ext == "jpeg"){
                $image = imagecreatefromjpeg($postvars["image_tmp"]);
            }
            else if($ext == "gif"){
                $image = imagecreatefromgif($postvars["image_tmp"]);
            }
            else if($ext == "png"){
                $image = imagecreatefrompng($postvars["image_tmp"]);
            }
            // Grab the width and height of the image.
            list($width,$height) = getimagesize($postvars["image_tmp"]);
            // If the max width input is greater than max height we base the new image off of that, otherwise we
            // use the max height input.
            // We get the other dimension by multiplying the quotient of the new width or height divided by
            // the old width or height.
            /*if($postvars["image_max_width"] > $postvars["image_max_height"]){
                if($postvars["image_max_width"] > $max_dimension){
                    $newwidth = $max_dimension;
                } else {
                    $newwidth = $postvars["image_max_width"];
                }
                $newheight = ($newwidth / $width) * $height;
            } else {
                if($postvars["image_max_height"] > $max_dimension){
                    $newheight = $max_dimension;
                } else {
                    $newheight = $postvars["image_max_height"];
                }
                $newwidth = ($newheight / $height) * $width;
            }*/
            // Create temporary image file.
            $tmp = imagecreatetruecolor($postvars["image_max_width"],$postvars["image_max_height"]);
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            // Copy the image to one with the new width and height.
            imagecopyresampled($tmp,$image,0,0,0,0,$postvars["image_max_width"],$postvars["image_max_height"],$width,$height);
            // Create random 4 digit number for filename.
            $rand = md5(uniqid(rand(), true));
            $filename = $dir.$rand.'.'.$ext;
            // Create image file with 100% quality.
            //imagejpeg($tmp,$filename,100);
            switch(strtolower($ext)){
              case "jpg":
              case "jpeg":
                imagejpeg($tmp,$filename,100) or die('Problem with saving');
                break;

              case "png":
                imagepng($tmp,$filename, 0, PNG_ALL_FILTERS) or die('Problem with saving');
                break;

              case "gif":
                imagegif($tmp,$filename) or die('Problem with saving');
                break;

              default:
                // if image format is unknown, and you whant save it as jpeg, maybe you should change file extension
                imagejpeg($tmp,$filename,100) or die('Problem with saving');
            }
            return $filename;
            imagedestroy($image);
            imagedestroy($tmp);
        } else {
            echo "<div class='red'>Ongeldig bestandstype. alleen jpg, jpeg, gif of png is toegestaan. maximaal 2mb.</div>";
            echo "<meta http-equiv='refresh' content='3;url=?page=account-options&category=picture'>";
        }
    } else {
        echo "<div class='red'>Ongeldig bestandstype. alleen jpg, jpeg, gif of png is toegestaan. maximaal 2mb.</div>";
            echo "<meta http-equiv='refresh' content='3;url=?page=account-options&category=picture'>";
    }
}
if(isset($_GET["do"])){
    if($_GET["do"] == "upload"){
        $uploaded = generate_resized_image();//now just returns path to new image
        $mysql = saveToDb($uploaded);
        if($mysql){
            echo "<div class='green'>Cover aangepast.</div>";
            echo "<meta http-equiv='refresh' content='3;url=?page=account-options&category=picture'>";
        }
        if (!$mysql) {
            //handle error
        }
    } else {
        $upload_and_resize = "";
    }
} else {
    $upload_and_resize = "";
}
function saveToDb($filename)
{
    global $db;
    
    $q = "UPDATE `gebruikers` SET `cover`=:file WHERE `user_id`=:userid";
    $st = $db->prepare($q);
    $st->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
    $st->bindParam(':file', $filename, PDO::PARAM_STR);
    $start = $st->execute();
    
    return true;

}
?>