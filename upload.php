<?php

header("X-Powered-By: Turu API");

/**
 * Konfigurasi
 * Ganti YOUR_SITE.TLD jadi hostname/domain kalian
 * Ganti DIRNAME jadi nama folder utk tempat penyimpanan file yg diupload, dan pastikan jga foldernya sudah dibuat ya
*/

$config = array(
	"target_dir" => "DIRNAME/",
	"target_file" => "DIRNAME/".time(),
	"max_size" => 10240000,
	"base-url" => "https://YOUR_SITE.TLD"
	);


/**
 * PROSES UPLOAD FILE
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	header("Content-type: application/json");
	if (isset($_FILES['image'])) {
		if (!empty($_FILES['image'])) {
				$mediaType = strtolower(pathinfo($_FILES['image']['name'])['extension']);
				if ($mediaType == 'png' || $mediaType == 'jpg' || $mediaType == 'jpeg' || $mediaType == 'gif' || $mediaType == 'svg' || $mediaType == 'ico' || $mediaType == 'webp' || $mediaType == 'mp4') {
					if ($_FILES['image']['size'] <= $config['max_size']) {
						if (move_uploaded_file($_FILES['image']['tmp_name'], $config['target_file'].".$mediaType")) {
							if ($_FILES['image']['size'] >= 1024000) {
								$bagi = 1024000;
								$satuan = "MB";
							} else {
								$bagi = 1024;
								$satuan = "KB";
							}
							$array = array("code" => "200", "status" => "OK", "result" => array("image" => "".$config['base-url']."/".$config['target_file'].".".$mediaType."", "file_size" => round($_FILES['image']['size'] / $bagi, 2). " $satuan", "created_at" => date('d-m-Y')));
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
			$array = array("code" => "400", "status" => "error", "message" => "empty image!");
		}
	} else {
		$array = array("code" => "404", "status" => "error", "message" => "missing image item to upload");
	}
	
	http_response_code(str_replace('""', '', $array["code"]));
	echo json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
	echo "<html>
	<head>
		<title>Upload - Storage Turu API</title>
	</head>
	<body>
		<h1>Online image storage powered by NaufalHoster.XYZ</h1>
		<br>
		<form method='POST' action='' enctype='multipart/form-data'>
			<input type='file' name='image' accept='.png, .gif, .jpeg, .jpg, .webp, .svg, .ico' required>
			<input type='submit' name='submit' value='Submit'>
		</form>
	</body>
</html>";
}



/**
 * AUTHOR: Muhammad Naufal Al Fattah (https://github.com/bangnopal)
 * 
 * THE END...
*/


?>
