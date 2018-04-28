<?php
class SystemDetails {

	//returns active url
	public static function getURL($as_string = false) {
		/**
		* Checks SITE_PATH and URL, delete SITE_PATH part from URL and return url
		*
		*/
		$url = explode('/', str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']));
		$sitePath = explode("/", SITE_PATH);
		$count = count($sitePath);

		$checkCounter = 0;
		for ($i = 0; $i < $count; $i++) {
			if ($sitePath[$i] != $url[$i]) {
				break;
			}
			$checkCounter++;
		}

		if ($checkCounter == $count) {
			for ($i = 0; $i < $count; $i++) {
				array_splice($url, 0, 1);
			}
		}
		if(!$as_string){
			return $url;
		}else{
			return implode('/', $url);
		}
	}

	public static function getClass($panelName, $class) {
		/**
		* imports necessary Classes
		*/
		switch ($panelName) {
			case CONTROL_PANEL:
				if (file_exists($file = "./controller/admin/" . $class . ".php")) {
					require_once($file);
				}
				if (file_exists($file = "./model/admin/" . $class . ".php")) {
					require_once($file);
				}
				break;
			default :
				if (file_exists($file = "./controller/visitor/" . $class . ".php")) {
					require_once($file);
				}
				if (file_exists($file = "./model/visitor/" . $class . ".php")) {
					require_once($file);
				}
				break;
		}
		
		if(file_exists($file = "./model/shared/".$class.".php")){
			require_once($file);
		}
		
		if (file_exists($file = "./core_lib/" . $class . ".php")) {
			require_once($file);
		}
		
		if (file_exists($file = "./user_lib/" . $class . ".php")) {
			require_once($file);
		}
		
		if (file_exists($file = "./" . $class . ".php")) {
			require_once($file);
		}
	}

	public static function getLink($admin=false){
		if(MULTILANG && !$admin){
			return SITE_LINK.$_SESSION["SELECTED_LANG"].'/';
		}else if($admin){
			return SITE_LINK.CONTROL_PANEL.'/';
		}else{
			return SITE_LINK;
		}
	}

	public static function linkActive($wantedLink, $contains=false){
		$currentLink = str_replace('/', '', SITE_LINK.SystemDetails::getURL(true));
		$wantedLink = str_replace('/', '', $wantedLink);

		if($contains && strpos($currentLink, $wantedLink) !== false){
			return true;
		} else if(!$contains && $currentLink === $wantedLink){
			return true;
		} else{
			return false;
		}
	}

	public static function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	public static function objToArray($obj, &$arr){

		if(!is_object($obj) && !is_array($obj)){
				$arr = $obj;
				return $arr;
		}

		foreach ($obj as $key => $value)
		{
				if (!empty($value))
				{
						$arr[$key] = array();
						self::objToArray($value, $arr[$key]);
				}
				else
				{
						$arr[$key] = $value;
				}
		}
		return $arr;
	}
}
