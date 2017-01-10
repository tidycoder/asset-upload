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

   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

      $expensions= array("jpeg","jpg","png","gif");

      if(in_array($file_ext,$expensions) === false){
         $errors[]="extension not allowed, please choose a JPEG or PNG or GIF file.";
      }

      if($file_size > 300*1024){
         $errors[]='File size must be less than 300KB';
      }

      $imageId = getGuid();
      $file_dest_name = $imageId.".".$file_ext;

      if(empty($errors)==true){
        $dest_file = "images/".$file_dest_name;
	$imageremote = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
         move_uploaded_file($file_tmp, $dest_file);
         $imagedata = getimagesize($dest_file);
         $image_width = $imagedata[0];
         $image_height = $imagedata[1];
         $res = array("success"=>true, "id"=>$imageId, "msg"=>"", "file_path"=>"http://".$imageremote."/php/".$dest_file, "image_width"=>$image_width, "image_height"=>$image_height);
         echo json_encode($res);
      }
      else{
         print_r($errors);
         $res = array("success"=>true, "msg"=>$errors, "file_path"=>"/assets/images/logo.png");
         echo json_encode($res);
      }
   }
?>
