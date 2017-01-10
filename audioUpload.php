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

   if(isset($_FILES['file'])){
      $errors = array();
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));
      
      
      if(empty($errors)==true){
        $file_dest_name = getGuid().".".$file_ext;
        move_uploaded_file($file_tmp,"audios/".$file_dest_name);
        $res = array("code"=>0, "file_path"=>"audios/".$file_dest_name);
        echo json_encode($res);
      }
      else{
         print_r($errors);
      }
   }
?>