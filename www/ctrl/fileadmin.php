<?php

require_once('./config/files.php');
require_once('./config/miscellaneous.php');
require_once('./core/controller.php');
require_once('./')
require_once('./core/uuid_factory.php');
require_once('./util/string.php');

class FileadminController extends Controller{

	private IDFactory $idFactory;

	public static function accessibility(string $method): string{
		switch ($method) {
			case 'media':
			case 'profilepicture':
			case 'sticker':
				return Miscellaneous::LEVEL_RANKS[0];
			case 'upload':
				return Miscellaneous::LEVEL_RANKS[1];
			default:
				return Miscellaneous::LEVEL_RANKS[256];
		}
	}

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

	function upload($params){
		switch(strtolower($params)){
			case 'media':
			case 'profilepicture':
			case 'sticker':
				try{
					if(!isset($_FILES['upload']['error'])){
						throw new RuntimeException('Some unknown error occured.');
					}
					if(is_array($_FILES['upload']['error'])){
						throw new RuntimeException('You can only upload one file at once.');
					}
					switch ($_FILES['upload']['error']) {
						case UPLOAD_ERR_OK:
							break;
						case UPLOAD_ERR_INI_SIZE:
						case UPLOAD_ERR_FORM_SIZE:
							throw new RuntimeException('The file you uploaded, seems to be a little bit too large.');
							break;
						case UPLOAD_ERR_PARTIAL:
							throw new RuntimeException('Somehow, the upload seems to have stopped midway.');
							break;
						case UPLOAD_ERR_NO_FILE:
							throw new RuntimeException('Well, we don\'t know, how you managed to, but you somehow didn\'t upload a file.');
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
						case UPLOAD_ERR_CANT_WRITE:
						case UPLOAD_ERR_EXTENSION:

							throw new RuntimeException('There seems to be an error on our side. Feel free to contact your favorite admin.');
							break;
						default:
							throw new RuntimeException('Some unknown error occured.');
							break;
					}
					if($_FILES['upload']['size'] > FileConfig::MAX_FILE_SIZE){
						throw new RuntimeException('Quite a large file, you uploaded there.');
					}
					$mime_type = mime_content_type($_FILES['upload']['tmp_name']);
					$file_ext = FileConfig::MIME_TYPE_EXTENSIONS[$mime_type];
					if(!in_array($mime_type, FileConfig::ALLOWED_MIME_TYPES[$params])){
						throw new RuntimeException('Unfortunally, the format of your file is not accepted for this upload.');
					}
					$file_name = $_FILES['upload']['name'];

					if(!isset($this->idFactory)){
						$this->idFactory = new UUIDFactory();
					}

					$file_uuid = $this->idFactory->create();

					move_uploaded_file($_FILES['upload']['tmp_name'], (FileConfig::DIR_DATA . '/' . 'uploads' . '/' . $file_uuid . '.' . $file_ext));

					$html = file_get_contents('./view/snippets/html/uploadok.html');

					$html = str_replace('<!-- uploaded file name -->', $file_name, $html);

					echo $html;
				}catch(RuntimeException $e){
					$html = file_get_contents('./view/snippets/html/uploaderr.html');
					
					$html = str_replace('<!-- upload error text -->', $e->getMessage(), $html);
					$html = str_replace('usage', $params, $html);

					echo $html;
				}
				break;
			default:
				if(StringUtils::stringStartsWith($params, 'area/')){
					$method = substr($params, 5);
					switch ($method) {
						case 'media':
						case 'profilepicture':
						case 'sticker':
							$html = file_get_contents('./view/snippets/html/upload.html');
							$html = str_replace('usage', $method, $html);
							$html = str_replace('<!-- upload text -->', FileConfig::UPLOAD_TEXTS[$method], $html);

							echo $html;
							break;
						default:
							exit();
							break;
					}
				}else{
					exit();
				}
				break;
		}
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