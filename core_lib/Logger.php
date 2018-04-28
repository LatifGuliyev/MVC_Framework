<?php

class Logger{
    public static function log($ex, $log_header){
        $log_content = "\n".date("H-i-s") .":\n{$log_header}\nFile Name: {$ex->getFile()} \nLine Number: {$ex->getLine()} \nMessage: {$ex->getMessage()} \n--------------------------------\n";
        $file_name = ERROR_LOG_DIR.date("Y-m-d").'.log';
        if(!file_exists($file_name)){
            $error_file = fopen($file_name, "w");
            fwrite($error_file, "");
            fclose($error_file);
        }
        error_log(rtrim($log_content), 3, $file_name);
    }
}

?>