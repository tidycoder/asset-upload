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
  $dirname = "audios/";
  file_put_contents($dirname.$filename, $data["body"]);
  $res = array("code" => 0, "src" => $dirname.$filename);
  echo json_encode($res);
?>
