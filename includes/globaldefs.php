<?php

$file = 'cache/globaldefs.php';

if (file_exists($file)) {
    if (filemtime($file) < time() - 86400) {
        unlink($file);
    }
}

if (file_exists($file)) {

    require_once($file);

} else {
    $getSettings = $db->prepare("SELECT * FROM `settings` WHERE `globaldef`=1");
    $getSettings->execute();
    $settings = $getSettings->fetchAll();

    $data = "<?php\n";

    foreach ($settings as $setting) {
        if(!empty($setting['value'])) {
            $data .= 'define("GLOBALDEF_' . strtoupper($setting['setting']) . '", "' . addslashes($setting['value']) . '");'."\n";
        }
    }

    #write the definitions file
    $fp = fopen($file,"w");
    fputs($fp, $data);
    fclose($fp);

    require_once($file);
    if (defined('GLOBALDEF_LANGUAGE')) {

        header("Refresh:0");

    } else {

        echo "ERROR: Set a language (nl or en) in your settings table";

        if (file_exists($file)) {
            unlink($file);
        }
        exit;
    }
}