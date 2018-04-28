<?php
class File {

	//for one file upload
	/*
    public static function uploadDocument($file, $address, $allowedExt, $create_dir=false) {
		$notifications = array("file_name"=>"", "error_occured"=>false, "error_message"=>"");
		$address = $_SERVER['DOCUMENT_ROOT'].$address;
        if($create_dir){
            if (!is_dir($address)) {
                mkdir($address, 0777, true);
            }
        }
        
		$exts = explode(".", $file['name']);
		$ext = strtolower(end($exts));
        $name = array_slice($exts, 0, -1);
        $name = implode("", $name);
        
		if (in_array($ext, $allowedExt)) {        
            $try = 0;
			$newname = $name.'.'.$ext;
			while (file_exists($address . $newname)) {
				$newname = $name."(".$try.").".$ext;
				$try++;
			}
			$file['name'] = $newname;
			
            try{
				if (move_uploaded_file($file['tmp_name'], $address . $file['name'])) {
					array_push($notifications, 'File Uploaded');
					$notifications['file_name'] = $file['name'];
					$notifications['error_occured'] = false;
					$notifications['error_message'] = "";
				} else{
					$notifications['file_name'] = "";
					$notifications['error_occured'] = true;
					$notifications['error_message'] = "Problem occured while uploading document. Please Contact with us!";
				}
			}catch(Exeption $e){
				$notifications['file_name'] = "";
				$notifications['error_occured'] = true;
				$notifications['error_message'] = $e->getMessage();
			}
		} else{
			$notifications['file_name'] = "";
			$notifications['error_occured'] = true;
			$notifications['error_message'] = "{$file['name']} File Extension is not allowed";
		}
		return $notifications;
	}
    */
    
    
    public static function uploadDocument($file, $file_name, $address, $allowedExt, $create_dir=false) {
		$notifications = array("file_name"=>"", "error_occured"=>false, "error_message"=>"");
        
		$address = $_SERVER['DOCUMENT_ROOT'].$address;
        
        if($create_dir){
            if (!is_dir($address)) {
                mkdir($address, 0777, true);
            }
        }
        
		$exts = explode(".", $file['name']);
		$ext = strtolower(end($exts));
        $name = array_slice($exts, 0, -1);
        $name = $file_name;
        
		if (in_array($ext, $allowedExt)) {        
            $try = 0;
			$newname = $name.'.'.$ext;
			while (file_exists($address . $newname)) {
				$newname = $name."(".$try.").".$ext;
				$try++;
			}
			$file['name'] = $newname;
			
            try{
				if (move_uploaded_file($file['tmp_name'], $address . $file['name'])) {
					array_push($notifications, 'File Uploaded');
					$notifications['file_name'] = $file['name'];
					$notifications['error_occured'] = false;
					$notifications['error_message'] = "";
				} else{
					$notifications['file_name'] = "";
					$notifications['error_occured'] = true;
					$notifications['error_message'] = "Problem occured while uploading document. Please Contact with us!";
				}
			}catch(Exeption $e){
				$notifications['file_name'] = "";
				$notifications['error_occured'] = true;
				$notifications['error_message'] = $e->getMessage();
			}
		} else{
			$notifications['file_name'] = "";
			$notifications['error_occured'] = true;
			$notifications['error_message'] = "{$file['name']} File Extension is not allowed";
		}
		return $notifications;
	}

	public static function addLogo_uploadDocument($file, $address, $allowedExt) {
		$notifications = array("file_name"=>"", "error_occured"=>false, "error_message"=>"");
		$address = $_SERVER['DOCUMENT_ROOT'].$address;
		$exts = explode(".", $file['name']);
		$ext = strtolower(end($exts));
		if (in_array($ext, $allowedExt)) {
            
			$try = 0;
			$newname = utf8_encode($file['name']);
            
			while (file_exists($address . $newname)) {
				$newname = $newname."(".$try.")";
				$try++;
			}
            
			$file['name'] = $newname;
			try{
				$target_name=$file['tmp_name']; //873 x 622

				$target_img = imagecreatefromjpeg($target_name);


				list($logo_width, $logo_height) = getimagesize(PHOTO_LOGO);

				$width = imagesx($target_img);
				$height = imagesy($target_img);

				$new_logo_height=$height/10;
				$new_logo_width=($new_logo_height/$logo_height)*$logo_width;

				$new_logo_img = imagecreatetruecolor($new_logo_width, $new_logo_height);
				$logo_img = imagecreatefrompng(PHOTO_LOGO);
				imagecopyresampled($new_logo_img, $logo_img, 0, 0, 0, 0, $new_logo_width, $new_logo_height, $logo_width, $logo_height);



				$tmp_img=imagecreatetruecolor(SMALL_PHOTO_WIDTH, SMALL_PHOTO_HEIGHT);
				imagecopyresized($tmp_img, $target_img, 0,0,0,0, SMALL_PHOTO_WIDTH, SMALL_PHOTO_HEIGHT, $width, $height);

				imagecopy($target_img, $new_logo_img, ($width-$new_logo_width), ($height-$new_logo_height), 0, 0, $new_logo_width, $new_logo_height);

				//imagegif($target_img);

				if (imagejpeg($target_img, $address."normal/".$file['name'], PHOTO_QUALITY) && imagejpeg($tmp_img, $address.$file['name'], 30)) {
					imagedestroy($target_img);
					imagedestroy($logo_img);

					array_push($notifications, 'File Uploaded');
					$notifications['file_name'] = $file['name'];
					$notifications['error_occured'] = false;
					$notifications['error_message'] = "";
				} else{
					$notifications['file_name'] = "";
					$notifications['error_occured'] = true;
					$notifications['error_message'] = "Problem occured while uploading document. Please Contact with us!";
				}
			}catch(Exeption $e){
				$notifications['file_name'] = "";
				$notifications['error_occured'] = true;
				$notifications['error_message'] = $e->getMessage();
			}
		} else{
			$notifications['file_name'] = "";
			$notifications['error_occured'] = true;
			$notifications['error_message'] = "{$file['name']} File Extension is not allowed";
		}
		return $notifications;
	}

	/*
	public static function uploadDocument($files, $adress, $allowedExt) {
		$notifications = array("file_name"=>"", "error_occured"=>false, "error_message"=>"");
		$exts = explode(".", $files['name']);
		$ext = strtolower(end($exts));
		if (in_array($ext, $allowedExt)) {
			$name = 0;
			$newname = $files['name'];
			while (file_exists($adress . $newname)) {
				$newname = $files['name'];
				$newname = $name . $newname;
				$name++;
			}
			$files['name'] = $newname;
			try{
				if (move_uploaded_file($files['tmp_name'], $adress . $files['name'])) {
					array_push($notifications, 'Dokument yuklendi');
					$notifications['file_name'] = $files['name'];
					$notifications['error_occured'] = false;
					$notifications['error_message'] = "";
					return $notifications;
				} else{
					$notifications['file_name'] = "";
					$notifications['error_occured'] = true;
					$notifications['error_message'] = "Dokumentin yuklenmesinde problem var Zəhmət olmasa Məsul Şəxslər ilə əlaqə saxlayın";
				}
			}catch(Exception ex){
				$notifications['file_name'] = "";
				$notifications['error_occured'] = true;
				$notifications['error_message'] = ;
			}
		} else{
			$notifications['file_name'] = "";
			$notifications['error_occured'] = true;
			$notifications['error_message'] = "{$files['name']} Dokument Sonlugu Dogru Deyil";
		}
		return $notifications;
	}
		*/

	public static function deleteDocument($address, $fileName) {
		$notifications = array("error_occured"=>false, "error_message"=>"");
		try{
			$address = $_SERVER['DOCUMENT_ROOT'].$address;
			if (file_exists($address . $fileName)) {
				if (unlink($address . $fileName)) {
					$notifications["error_occured"]=false;
					$notifications["error_message"]="";
				} else {
					$notifications["error_occured"]=true;
					$notifications["error_message"]="Problem occured while deleting file. Please Contact with us!";
				}
			} else {
				$notifications["error_occured"]=true;
				$notifications["error_message"]="File doesn't exist Address: {$address} | FileName: {$fileName}";
			}
		}catch(Exception $e){
			$notifications["error_occured"]=true;
			$notifications["error_message"]=$e->getMessage();
		}
		return $notifications;
	}

}

?>
