<?php

// function created by www.thewebhelp.com

if(!function_exists("create_square_image")){
	function create_square_image($original_file, $destination_file=NULL, $square_size = 640){

		if(isset($destination_file) and $destination_file!=NULL){
			if(!is_writable($destination_file)){
				// echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';
			}
		}

		// get width and height of original image
		$imagedata = getimagesize($original_file);
		$original_width = $imagedata[0];
		$original_height = $imagedata[1];

		if($original_width > $original_height){
			$new_height = $square_size;
			$new_width = $new_height*($original_width/$original_height);
		}
		if($original_height > $original_width){
			$new_width = $square_size;
			$new_height = $new_width*($original_height/$original_width);
		}
		if($original_height == $original_width){
			$new_width = $square_size;
			$new_height = $square_size;
		}

		$new_width = round($new_width);
		$new_height = round($new_height);

		// load the image
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			$original_image = imagecreatefromjpeg($original_file);
		}
		if(substr_count(strtolower($original_file), ".gif")){
			$original_image = imagecreatefromgif($original_file);
		}
		if(substr_count(strtolower($original_file), ".png")){
			$original_image = imagecreatefrompng($original_file);
		}

		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		$square_image = imagecreatetruecolor($square_size, $square_size);

		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

		if($new_width>$new_height){
			$difference = $new_width-$new_height;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $square_size+$difference, $square_size, $new_width, $new_height);
		}
		if($new_height>$new_width){
			$difference = $new_height-$new_width;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
		}
		if($new_height == $new_width){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
		}

		$quality = 90;

		// if no destination file was given then display a png
		if(!$destination_file){
			imagepng($square_image,NULL,$quality);
		}

		$exif = exif_read_data($original_file);
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
			case 8:
			    $square_image = imagerotate($square_image,90,0);
			    break;
			case 3:
			    $square_image = imagerotate($square_image,180,0);
			    break;
			case 6:
			    $square_image = imagerotate($square_image,-90,0);
			    break;
		    }
		}

		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			imagejpeg($square_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($square_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($square_image,$destination_file,0);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);

	}
}

if(!function_exists("create_compressed_image")){
	function create_compressed_image($original_file, $destination_file=NULL, $max_width = 640){

		if(isset($destination_file) and $destination_file!=NULL){
			if(!is_writable($destination_file)){
				// echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';
			}
		}

		// get width and height of original image
		$imagedata = getimagesize($original_file);
		$original_width = $imagedata[0];
		$original_height = $imagedata[1];
		$new_width = $original_width;
		$new_height = $original_height;

		if($original_width > $max_width){
			$new_width = $max_width;
			$new_height = $new_width*($original_height/$original_width);
		}

		$new_width = round($new_width);
		$new_height = round($new_height);

		// load the image
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			$original_image = imagecreatefromjpeg($original_file);
		}
		if(substr_count(strtolower($original_file), ".gif")){
			$original_image = imagecreatefromgif($original_file);
		}
		if(substr_count(strtolower($original_file), ".png")){
			$original_image = imagecreatefrompng($original_file);
		}

		$smaller_image = imagecreatetruecolor($new_width, $new_height);

		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);


		$quality = 90;

		// if no destination file was given then display a png
		if(!$destination_file){
			imagepng($smaller_image,NULL,$quality);
		}

		$exif = exif_read_data($original_file);

		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
			case 8:
			    $smaller_image = imagerotate($smaller_image,90,0);
			    break;
			case 3:
			    $smaller_image = imagerotate($smaller_image,180,0);
			    break;
			case 6:
			    $smaller_image = imagerotate($smaller_image,-90,0);
			    break;
		    }
		}
		
		
		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			imagejpeg($smaller_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($smaller_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($smaller_image,$destination_file,0);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);

	}
}

if(!function_exists("create_snapshot_thumbnail")){
	function create_snapshot_thumbnail($original_file, $destination_file=NULL, $square_size = 640){
		if(isset($destination_file) and $destination_file!=NULL){
			if(!is_writable($destination_file)){
				// echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';
			}
		}

		// get width and height of original image
		$imagedata = getimagesize($original_file);
		$original_width = $imagedata[0];
		$original_height = $imagedata[1];

		if($original_width > $original_height){
			$new_height = $square_size;
			$new_width = $new_height*($original_width/$original_height);
		}
		if($original_height > $original_width){
			$new_width = $square_size;
			$new_height = $new_width*($original_height/$original_width);
		}
		if($original_height == $original_width){
			$new_width = $square_size;
			$new_height = $square_size;
		}

		$new_width = round($new_width);
		$new_height = round($new_height);

		// load the image
		if(substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			$original_image = imagecreatefromjpeg($original_file);
		}
		if(substr_count(strtolower($original_file), ".gif")){
			$original_image = imagecreatefromgif($original_file);
		}
		if(substr_count(strtolower($original_file), ".png")){
			$original_image = imagecreatefrompng($original_file);
		}

		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		if($new_width>$new_height){
		    $square_image = imagecreatetruecolor($new_width, $new_height);
		}else{
		    $square_image = imagecreatetruecolor($square_size, $square_size);
		}

		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

		if($new_width>$new_height){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $new_width, $new_height, $new_width, $new_height);
		}
		if($new_height>$new_width){
			$difference = $new_height-$new_width;
			$half_difference =  round($difference/2);
			imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $square_size, $square_size+$difference, $new_width, $new_height);
		}
		if($new_height == $new_width){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
		}

		$quality = 90;

		// if no destination file was given then display a png
		if(!$destination_file){
			imagepng($square_image,NULL,$quality);
		}

		$exif = exif_read_data($original_file);
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
			case 8:
			    $square_image = imagerotate($square_image,90,0);
			    break;
			case 3:
			    $square_image = imagerotate($square_image,180,0);
			    break;
			case 6:
			    $square_image = imagerotate($square_image,-90,0);
			    break;
		    }
		}

		// save the smaller image FILE if destination file given
		if(substr_count(strtolower($destination_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")){
			imagejpeg($square_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".gif")){
			imagegif($square_image,$destination_file,$quality);
		}
		if(substr_count(strtolower($destination_file), ".png")){
			imagepng($square_image,$destination_file,0);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);
	}
}
?>
