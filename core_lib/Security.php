<?php

class Security extends View{

	public static function checkInput($input) {
		if (is_array($input)) {
			foreach($input as $key=>$value){
				if(is_array($value)){
					$input[$key] = self::checkInput($value);
				}else{
					$value = trim($value);
					$value = stripslashes($value);
					$value = htmlspecialchars($value);
					$input[$key] = $value;
				}
			}
			return $input;
		} else {
			$input = trim($input);
			$input = stripslashes($input);
			$input = htmlspecialchars($input);
			return $input;
		}
	}
}

?>
