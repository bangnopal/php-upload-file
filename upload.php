<?php

header("X-Powered-By: Turu API");
header("Content-type: application/json");

/**
 * Konfigurasi
*/

$config = array(
	"target_dir" => "uploads/",
	"target_file" => "uploads/".time(),
	"max_size" => 1000000,
	"base-url" => "https://naufalhoster.xyz"
	);


/**
 * PROSES UPLOAD FILE
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$checkSize = getimagesize($_FILES['image']['tmp_name']);
	if ($checkSize !== false) {
		$mediaType = strtolower(pathinfo($_FILES['image']['name'])['extension']);
		if ($mediaType == 'png' || $mediaType == 'jpg' || $mediaType == 'jpeg' || $mediaType == 'gif') {
			if ($_FILES['image']['size'] <= $config['max_size']) {
				if (move_uploaded_file($_FILES['image']['tmp_name'], $config['target_file'].".$mediaType")) {
					$array = array("code" => "200", "status" => "OK", "result" => array("image" => "".$config['base-url']."/dir/".$config['target_file'].".".$mediaType."", "created_at" => date('d-m-Y')));
				} else {
					$array = array("code" => "503", "status" => "error", "message" => "something went wrong when uploading your files, please check error_log");
				}
			} else {
				$array = array("code" => "400", "status" => "error", "message" => "your file is too large");
			}
		} else {
			$array = array("code" => "400", "status" => "error", "message" => "media type not allowed");
		}
	} else {
		$array = array("code" => "400", "status" => "error", "message" => "media type not allowed");
	}
} else {
	$array = array("code" => "405", "status" => "error", "message" => "method not allowed");
}

http_response_code(str_replace('""', '', $array["code"]));
echo json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPESD_SLASHES);


/**
 * AUTHOR: Muhammad Naufal Al Fattah (https://github.com/bangnopal)
 * 
 * THE END...
*/


?>