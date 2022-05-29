<?php

require_once('./config/files.php');
require_once('./core/controller.php');

class FileadminController extends Controller{

	private function prepare_send_file(string $location, ?bool $is_public = true){
		if(file_exists($location)){
			if($is_public){
				header('Cache-Control: public, no-cache');
			}
			$last_modified = filemtime($location);
			if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
				$modified_since = date_create_from_format(RFC2822, $_SERVER['HTTP_IF_MODIFIED_SINCE']);
				if($modified_since < $last_modified){
					header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
					header('Connection: close');
					exit();
				}else{
					$this->send_file($location);
				}
			}else{
				$this->send_file($location);
			}
		}else{
			http_response_code(404);
		}
	}

	private function send_file(string $location){
		$path_parts = pathinfo($location);
		$last_modified = filemtime($location);
		switch(strtolower($path_parts['extension'])){
			case 'png':
				header('Content-Type: image/png');
				break;
			case 'webp':
				header('Content-Type: image/webp');
				break;
		}
		header('Content-Length: ' . filesize($location));
		header('Last-Modified: ' . date(DATE_RFC2822, $last_modified));
		readfile($location);
	}

	function media($params){
		$filepath = FileConfig::DIR_DATA . '/' . 'media' . '/' . $params;
		$this->prepare_send_file($filepath);
	}

	function profilepicture($params){
		$filepath = FileConfig::DIR_DATA . '/' . 'profilepicture' . '/' . $params;
		$this->prepare_send_file($filepath);
	}

	function sticker($params){
		$filepath = FileConfig::DIR_DATA . '/' . 'sticker' . '/' . $params;
		$this->prepare_send_file($filepath);
	}

}

?>