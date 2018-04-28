<?php

class HTML {

	public static function inputSelect($input_name, $input_value=array('attribute'=>'', 'values'=>''), $attributes){
		$input_string = "<select name='{$input_name}' {$attributes}>";
		$input_value_size = sizeof($input_value);
		$input_string .= "<option value = ''></option>";
		for($i=0; $i<$input_value_size; $i++){
			foreach($input_value['values'][$i] as $key=>$value){
				$input_string .= "<option value = 'combo_{$key}' {$input_value['attributes'][$i]} >{$value}</option>";
			}
		}
		$input_string .= '</select>';
		return $input_string;
	}

	public static function inputTypes($input_format, $input_name, $input_value, $input_placeholder, $attributes){
		if(is_array($attributes)){
			$attributes = implode(", ", $attributes);
		}
		$input_string = '';
		if($input_format == 'select'){
			if(!is_array($input_value)){
				throw new Exception('Wrong value for input type: '.$input_format);
			}
			$input_string .= "<select name='{$input_name}' {$attributes}>";
			$input_value_size = sizeof($input_value);
			$input_string .= "<option value = ''></option>";
			for($i=0; $i<$input_value_size; $i++){
				foreach($input_value[$i] as $key=>$value){
					$input_string .= "<option value = 'combo_{$key}'>{$value}</option>";
				}
			}

			$input_string .= '</select>';
		}
		else if(in_array($input_format, array('text', 'email', 'password', 'number'))){
			if(is_array($input_value)){
				$input_value = implode('_', $input_value);
			}
			$input_string = "<input type = '{$input_format}' name = '{$input_name}' value = '{$input_value}' placeholder = '{$input_placeholder}' {$attributes}>";
		}else if($input_format=='textarea'){
			$input_string .= "<textarea name = '{$input_name}' {$attributes}>{$input_value}</textarea>";
		}
		else{
			throw new Exception('Wrong input format: '.$input_format);
		}
		return $input_string;
	}

	public static function table($header = array(), $data = array(), $property = array()) {
		$var = '';
		foreach ($property as $key => $val)
			$var.=$key . " = \"" . $val . "\" ";
		$table = "<table {$var}>";
		if ($header)
			$table.="<tr><th>" . implode("</th><th>", $header) . "</th></tr>";
		foreach ($data as $d)
			$table.="<tr><td>" . implode("</td><td>", $d) . "</td></tr>";
		$table.="</table>";
		return $table;
	}

	public static function convertImageArray($data, $key) {
		for ($i = 0; $i < sizeof($data); $i++) {
			$data[$i][$key] = "<img src = '{$data[$i][$key]}'>";
		}
		return $data;
	}

	public static function convertLinkArray($data, $key, $label) {
		for ($i = 0; $i < sizeof($data); $i++) {
			$data[$i][$key] = "<a href = '{$data[$i][$key]}'>{$label}</a>";
		}
		return $data;
	}

	public static function month($month) {
		$name = "";
		switch ($month) {
			case 1:
				$name = "Yanvar";
				break;
			case 2:
				$name = "Fevral";
				break;
			case 3:
				$name = "Mart";
				break;
			case 4:
				$name = "Aprel";
				break;
			case 5:
				$name = "May";
				break;
			case 6:
				$name = "İyun";
				break;
			case 7:
				$name = "İyul";
				break;
			case 8:
				$name = "Avqust";
				break;
			case 9:
				$name = "Sentyabr";
				break;
			case 10:
				$name = "Oktyabr";
				break;
			case 11:
				$name = "Noyabr";
				break;
			case 12:
				$name = "Dekabr";
				break;
			default:
				$name = "Səhvlik";
		}
		return $name;
	}

	public static function date($date) {
		$arrayDate = explode(" ", $date);
		$arrayPartDate = explode("-", $arrayDate[0]);
		$arrayPartHour = explode(":", $arrayDate[1]);

		$result['year'] = $arrayPartDate[0];
		$result['month'] = $arrayPartDate[1];
		$result['day'] = $arrayPartDate[2];
		$result['hour'] = $arrayPartHour[0];
		$result['minute'] = $arrayPartHour[1];
		$result['second'] = $arrayPartHour[2];
		return $result;
	}
    
    public static function convert_date_to_russian($date){
        $parts = explode (" ", $date);
        $now = new DateTime();
        $d = new DateTime($parts[0]);
        $diff = $now->diff($d);
        $r = $diff->d;
        if($r == 1){
            return 'вчерa, '.$parts[1];
        }
        else if($r == 0){
            return 'Cегодня, '.$parts[1];
        }
        else{
            $arrayPartDate = explode("-", $parts[0]);
            $day = $arrayPartDate[0];
            $month = strtolower($arrayPartDate[1]);
            $year = $arrayPartDate[2];
            switch($month){
                case "january":
                    return "$day-январь-$year";
                case "february":
                    return "$day-февраль-$year";
                case "march":
                    return "$day-Март-$year";
                case "april":
                    return "$day-апрель-$year";
                case "may":
                    return "$day-май-$year";
                case "june":
                    return "$day-июнь-$year";
                case "july":
                    return "$day-июль-$year";
                case "august":
                    return "$day-августейший-$year";
                case "september":
                    return "$day-сентябрь-$year";
                case "october":
                    return "$day-октябрь-$year";
                case "november":
                    return "$day-ноябрь-$year";
                case "december":
                    return "$day-Декабрь-$year";
            }
        }
    }

}

?>
