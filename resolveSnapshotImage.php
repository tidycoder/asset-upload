<?php
    function getGUID(){
        if (function_exists('com_create_guid')){
        return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = "";//chr(45);// "-"
            $uuid = ""
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }

    require_once "thumbs.php";
    $post_data = json_decode(file_get_contents('php://input'), true);
    function getimg($url) {
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg, image/png';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'php';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $useragent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }
    $snapshotUrl = $post_data['snapshotUrl'];
    $image = getimg($snapshotUrl);
    $file_ext = pathinfo($snapshotUrl, PATHINFO_EXTENSION);
    $guid = getGuid();
    $file_dest_name = $guid.'.'.$file_ext;
    $dest_file = "images/".$file_dest_name;
    file_put_contents($dest_file, $image);
    $imagedata = getimagesize($dest_file);
    $image_width = $imagedata[0];
    $image_height = $imagedata[1];
    create_snapshot_thumbnail($dest_file, "images/thumbs/".$file_dest_name, 320);
    echo $dest_file;
?>
