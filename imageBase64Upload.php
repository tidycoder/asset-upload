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


  $postData=file_get_contents('php://input', true); 
  $params = json_decode($postData, true);
  $img = $params['img'];
  $img = str_replace('data:image/png;base64,', '', $img);
  $img = str_replace(' ', '+', $img);
  $data = base64_decode($img);
  $filename = getGuid();
  $file_dest_name = 'images/'.$filename.'.png';
  $success = file_put_contents($file_dest_name, $data);
  $imagedata = getimagesize($file_dest_name);
  $image_width = $imagedata[0];
  $image_height = $imagedata[1];
  create_square_image($file_dest_name, "images/thumbs/".$filename.".png");
  
  $res = array("code"=>0, "file_path"=>$file_dest_name, "image_width"=>$image_width, "image_height"=>$image_height);
  echo json_encode($res);
?>
