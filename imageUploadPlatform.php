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

   if(isset($_FILES['image_file'])){
      $errors= array();
      $file_name = $_FILES['image_file']['name'];
      $file_size =$_FILES['image_file']['size'];
      $file_tmp =$_FILES['image_file']['tmp_name'];
      $file_type=$_FILES['image_file']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image_file']['name'])));

      $expensions= array("jpeg","jpg","png", "gif");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }

      if($file_size > 300*1024){
         $errors[]='File size must be less than 300KB';
      }

      $file_dest_name = getGuid().".".$file_ext;
      if(empty($errors)==true){
        $dest_file = "images/".$file_dest_name;
        move_uploaded_file($file_tmp, $dest_file);
        $imagedata = getimagesize($dest_file);
        $image_width = $imagedata[0];
        $image_height = $imagedata[1];
        $res = array("files"=>array(array("error"=>0, "name"=>$file_dest_name, "size"=>$file_size, "url"=>$dest_file, "thumbnailUrl"=>"images/thumbs/".$file_dest_name, "deleteUrl"=>$dest_file, "deleteType"=>"DELETE", "image_width"=>$image_width, "image_height"=>$image_height)));
        echo json_encode($res);
        if ($file_ext != "gif") {
          create_square_image($dest_file, "images/thumbs/".$file_dest_name, 300);
        }
      }
      else{
         print_r($errors);
         $res = array("files"=>array(array("name"=>$file_name, "size"=>0, "error"=>"upload image server internal error")));
         echo json_encode($res);
      }
   }
   else {
      $errors[]="_FILES['file'] isn't set";
      print_r($errors);
      $res = array("files"=>array(array("name"=>"unknown", "size"=>0, "error"=>"upload image server _FILES isn't set")));
      echo json_encode($res);
   }
?>
