<?php
session_start();
set_error_handler('exceptions_error_handler');

/* create Exceptions for everything  */
function exceptions_error_handler($severity, $message, $filename, $lineno) {
	if (error_reporting() == 0) {
		return;
	}
	if (error_reporting() & $severity) {
		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}
}

/*
set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
		// error was suppressed with the @-operator
		if (0 === error_reporting()) {
				return false;
		}

		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});
*/
/*
./ create Exceptions for everything
*/

try{
	//$time = microtime(TRUE);

	//$mem = memory_get_usage();

	require_once "core_lib/SystemDetails.php";
	require_once("constants/systemConstants.php");
	require_once "constants/userConstants.php";

	$url = SystemDetails::getURL();

	// clean last element for GET
	$last_index = count($url) - 1;
	$posiotion=strpos($url[$last_index], "?");
	if($posiotion){
		$url[$last_index]=$variable = substr($url[$last_index], 0, $posiotion);
	}
	// ./ clean last element for GET


	$index = 0;
	$increment = 0;// for multilang false
	/*
	if lang false index 0 shows class name
	if lang true index 1 shows class name 0 shows selected lang
	*/

	$_SESSION["SELECTED_LANG"] = DEFAULT_LANG;

	if (MULTILANG) {
		if(isset($url[$index]) && !empty($url[$index])){
			if(in_array($url[$index], LANGS)){
				$_SESSION["SELECTED_LANG"] = $url[$index];
			}
		}
		$index = 1;
	}else{
		if($url[0]=="admin"){
			$increment=1;
		}
	}

	spl_autoload_register(function($class) {
		$url = SystemDetails::getURL();
		SystemDetails::getClass($url[0], $class);
	});

	if (class_exists($sinif = ucfirst(!empty($url[$index + $increment]) ? $url[$index + $increment] : 'Index') . 'Controller')) {
		$kontrol = new $sinif();
		if($sinif=='IndexController'){
			array_unshift($url, 'index');
			$url[$index + $increment] = 'index';
		}
	} else {
		array_unshift($url, 'index');
		$url[$index + $increment] = 'index';
		$kontrol = new IndexController();
	}

	// if function and variables declared
	if (isset($url[$index + 1 + $increment]) && method_exists($kontrol, $url[$index + 1 + $increment])) {
		$a = $url[$index + 1 + $increment];
		array_splice($url, 0, $index + 2 + $increment);
		if (empty(end($url))) {
			array_pop($url);
		}
		call_user_func_array(array($kontrol, $a), $url);
	}
	// if function declared not class autoclass is INDEX class
	elseif (isset($url[$index+$increment]) && method_exists($kontrol, $url[$index+$increment])) {
		$a = $url[$index+$increment];
		array_splice($url, 0, $index+$increment);
		if (empty(end($url))) {
			array_pop($url);
		}
		call_user_func_array(array($kontrol, $a), $url);
	}
	// if nothing found call index function
	else if (method_exists($kontrol, 'index')) {
		array_splice($url, 0, $index + 1 + $increment);
		if (empty(end($url))) {
			array_pop($url);
		}
		$a='index';
		call_user_func_array(array($kontrol, $a), $url);
		//print_r($url);
		//$kontrol->index();
	}
	// if index function doesn't found call notfound
	else {
		die((new View())->viewDoc(array('not_found')));
	}
}catch(Exception $ex){
    Logger::log($ex, 'INDEX ERROR ');
    (new View())->viewDoc(array('visitor/not_found'));
    Notifications::notify('Error', 'May be you do not have permission for that page', 'error', false);
    die();
}
/*
print_r(array(
	'memory' => (memory_get_usage() - $mem) / (1024 * 1024),
	'seconds' => microtime(TRUE) - $time,
	"langs" => $_SESSION["SELECTED_LANG"],
));
*/
?>
