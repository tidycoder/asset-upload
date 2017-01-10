<?php
	$data = json_decode(file_get_contents("php://input"), true);
    $filename = $data['name'];
    if (file_exists($filename)) {
        echo filesize($filename);
    } else {
        echo "0";
    }
?>