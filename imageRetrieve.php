<?php
  require_once "thumbs.php";
  require_once "weixinUtil.php";

  $json = json_decode(file_get_contents("php://input"));
  $url = $json->retrieveUrl;
  $guid = getGUID();
  $data = downloadWeixinFile($url);
  preg_match('/\w\/(\w+)/i', $data["header"]["content_type"], $extmatches);
  $file_ext = $extmatches[1];
  $filename = $guid.'.'.$file_ext;
  $dirname = "images/";
  $dest_file = $dirname.$filename;
  file_put_contents($dest_file, $data["body"]);


  $imagedata = getimagesize($dest_file);
  $image_width = $imagedata[0];
  $image_height = $imagedata[1];
  create_square_image($dest_file, "images/thumbs/".$filename);
  $res = array("code"=>0, "src" => $dest_file, "file_path"=>$dest_file, "image_width"=>$image_width, "image_height"=>$image_height);
  echo json_encode($res);


?>
