<?php

require_once('./config/files.php');


class Logger{
	public static function print(?string $message){
		if (!empty($message)){
			file_put_contents(
				FileConfig::DIR_LOG . '/registration.log',
				$message . PHP_EOL, FILE_APPEND
			);
		}
	}
}

?>