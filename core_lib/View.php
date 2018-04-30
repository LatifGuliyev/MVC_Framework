<?php
class View {
	private function template($c) {
		$c = str_replace(
				array('{{', '}}', '{!', '!}', '{[', ']}', '@{', '@}'),
				array('<?php echo ', ';?>','<?=htmlspecialchars(', ');?>', '<?=$lang(\'', '\');?>', '<?php ', ' ?>'), $c);
		return $c;
	}

	public function viewDocMem(array $files, array $data = array(), $view_dir = './view/') {
		$time = CACHE_DURATION;
		$name = 'cache/' . md5(end($files).$_SESSION["SELECTED_LANG"]) . '.html.php';
		if (!file_exists($name) || time() - $time > filemtime($name)) {
			$exp = "Expires: ".  gmdate("D, d M Y H:i:s", time()+$time)." GMT";
			$c = '';
			ob_start();
			extract($data, EXTR_SKIP);
			foreach ($files as $file) {
				include($view_dir . $file . '.php');
			}
			$c = ob_get_clean();
			$c = self::template($c);
			$php = '<?php
							//if(!ob_start("ob_gzhandler")) ob_start();
							header("Content-type: text/html; charset: UTF-8");
							header("Cache-Control: must-revalidate");
							header("'.$exp.'");
							$_lang = parse_ini_file("lang/" . $_SESSION["SELECTED_LANG"] . ".ini");
							$lang = function($m) use($_lang){return isset($_lang[trim($m)]) ? $_lang[trim($m)] : $m;};
						?>
						';
			/*if ($data) {
				foreach ($data as $key => $val) {
					$$key = $val;
				}
			}*/
            
			
            ob_start();
			eval("?> $php$c <?php ");
            $c = ob_get_clean();
            echo $c;
            file_put_contents($name,$c);
		} else {
            extract($data, EXTR_SKIP);
            $content = file_get_contents($name);
			eval("?>$content<?php ");
		}
	}

	public function viewDoc(array $files, array $data = array(), $view_dir = './view/', $add_header = 1) {
		$c = '';
		ob_start();
		extract($data, EXTR_SKIP);
		foreach ($files as $file) {
			include($view_dir . $file . '.php');
		}
		$c = ob_get_clean();
		$c = self::template($c);

		/*if ($data) {
			foreach ($data as $key => $val) {
				$$key = $val;
			}
		}*/

		if($add_header){
			$php = '<?php
			$_lang = parse_ini_file("lang/" . $_SESSION["SELECTED_LANG"] . ".ini");
			$lang = function($m) use($_lang){return isset($_lang[trim($m)]) ? $_lang[trim($m)] : $m;};
			?>';
			eval("?>$php$c<?php ");
		}else{
			eval("?>$c<?php ");
		}

	}

}

?>
