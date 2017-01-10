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

   if(isset($_FILES['file'])){
      $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));
      
      $expensions= array("jpeg","jpg","png", "gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG or GIF file.";
      }
      
      if($file_size > 20*1024*1024){
         $errors[]='File size must be excately 20 MB';
      }
      
      if(empty($errors)==true){
        if (isset($_POST['origin']) && $_POST['origin'] == "true") {
          $guid = getGuid();
          $file_dest_name = $guid."-orig.".$file_ext;
          move_uploaded_file($file_tmp,"images/origin/".$file_dest_name);

          $original_file = "images/origin/".$file_dest_name;
          $imagedata = getimagesize($original_file);
          $original_width = $imagedata[0];
          $original_height = $imagedata[1];
          $exif = exif_read_data($original_file);
          if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
              case 8:
              case 6:
                $image_temp = $original_width;
                $original_width = $original_height;
                $original_height = $image_temp;
                break;
              default:
                break;
            }
          }
          $image_width = $original_width;
          $image_height = $original_height;

          if ($file_ext != "gif") {
            create_square_image($original_file, "images/thumbs/".$file_dest_name);
            if($original_width > 640){
              $image_width = 640;
              $image_height = $image_width*($original_height/$original_width);
            }
            $image_width = round($image_width);
            $image_height = round($image_height);
            create_compressed_image($original_file, "images/".$file_dest_name, 640);
          }

          $res = array("code"=>0, "file_path"=>"images/".$file_dest_name, "image_width"=>$image_width, "image_height"=>$image_height);
          echo json_encode($res);

        } else {
          $file_dest_name = getGuid().".".$file_ext;
          $dest_file = "images/".$file_dest_name;
          move_uploaded_file($file_tmp, $dest_file);
          $imagedata = getimagesize($dest_file);
          $image_width = $imagedata[0];
          $image_height = $imagedata[1];
          create_square_image($dest_file, "images/thumbs/".$file_dest_name);
          $res = array("code"=>0, "file_path"=>"images/".$file_dest_name, "image_width"=>$image_width, "image_height"=>$image_height);
          echo json_encode($res);
        }
      }
      else{
         print_r($errors);
      }
   }
?>