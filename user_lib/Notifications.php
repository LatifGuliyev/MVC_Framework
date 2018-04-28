<?php
class Notifications {

	public static function notify($title, $text, $type, $bootstrap=true) {
		if($bootstrap){
			echo "
				<script type='text/javascript'>
							$(function(){
								new PNotify({
								title: \"{$title}\",
								text: \"{$text}\",
								type: \"{$type}\",
								styling: 'bootstrap3'
								});
							});
				</script>
				";
		}else{
			echo "
				<script type='text/javascript'>
					$.notify(\"{$title}: {$text}\", \"{$type}\");
				</script>
			";
		}

	}

	public static function showNotifications($success_messages, $warning_messages, $error_messages, $bootstrap=true){
		if($bootstrap){
			if(is_array($success_messages) && is_array($warning_messages) && is_array($error_messages)){
				foreach($success_messages as $m){
					self::notify('Success', $m, 'success');
				}
				foreach($warning_messages as $m){
					self::notify('Warning', $m, 'warning');
				}
				foreach($error_messages as $m){
					self::notify('Error', $m, 'error');
				}
			}else{
				throw new Exception("Array values are expected for showNotifications function");
			}
		}
	}

}

?>
