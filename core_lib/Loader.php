<?php

class Loader{

	public static function loadPlugin($plugin_name){
		$dir = $_SERVER['DOCUMENT_ROOT'].PLUGIN_DIR.'/'.$plugin_name;
		if(is_file($dir)){
			$exts = explode(".", $dir);
			$ext = strtolower(end($exts));
			if($ext=='php'){
				require $dir;
			}
			return;
		}

		$allFiles = scandir($dir);
		$files = array_diff($allFiles, array('.', '..'));

		foreach($files as $f){
			if(is_file($dir.'/'.$f)){
				$exts = explode(".", $f);
				$ext = strtolower(end($exts));
				if($ext=='php'){
					require $dir.'/'.$f;
				}
			}else{
				self::loadPlugin($plugin_name.'/'.$f);
			}
		}

	}

}

?>
