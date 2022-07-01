<?php

require_once('./config/files.php');
require_once('./core/logger.php');
require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./util/uuid.php');

class FileUtils{

	public static function insertUploadArea($html, $usage){
		$upload_file = file_get_contents('./view/snippets/html/uploadwrapper.html');
		$upload_wrapper = StringUtils::stringBetweenTwoStrings($upload_file, '<!-- upload wrapper begin -->', '<!-- upload wrapper end -->');
		$upload_wrapper = str_replace('usage', $usage, $upload_wrapper);
		$styles = StringUtils::stringBetweenTwoStrings($upload_file, '<!-- upload wrapper styles begin -->', '<!-- upload wrapper styles end -->');

		$html = str_replace('<!-- upload wrapper stylesheets -->', $styles, $html);
		$html = str_replace('<!-- upload wrapper placeholder -->', $upload_wrapper, $html);
		return $html;
	}

	public static function confirm($usage): array{
		if(!in_array($usage, FileConfig::UPLOAD_USAGES)){
			return array('error' => FileConfig::CONFIRM_ERRORS['Invalid usage']);
		}
		$file_uuid = $_SESSION[FileConfig::FILE_UPLOADS[$usage]];
		if(!isset($file_uuid)){
			return array('error' => FileConfig::CONFIRM_ERRORS['No given ID']);
		}
		$sql  = 'SELECT U.ID, U.Extension, U.UploaderID, U.UploadTime, T.Type, T.Name';
		$sql .= '  FROM Upload AS U, UploadType AS T';
		$sql .= '  WHERE ID = :ID';
		$sql .= '    AND U.Type = T.Type;';

		$params = array(':ID' => $file_uuid);

		$cmd = new SQLCommand($sql, $params);
		$sql_result = $cmd->execute();

		if($sql_result->isEmpty()){
			return array('error' => FileConfig::CONFIRM_ERRORS['Missing DB entry']);
		}else{
			$row = $sql_result->getRow();
			if($usage != $row['Name']){
				return array('error' => FileConfig::CONFIRM_ERRORS['Different usages']);
			}
		}

		$location = FileConfig::DIR_DATA . '/' . 'uploads' . '/' . UUIDUtils::strip($row['ID']) . '.' . $row['Extension'];

		if(!file_exists($location)){
			return array('error' => FileConfig::CONFIRM_ERRORS['Missing file']);
		}

		$info = array();

		switch($usage){
			case 'media':
				$info = array(
					'error' => FileConfig::CONFIRM_ERRORS['OK'],
					'extension' => $row['Extension'],
					'mediaID' => $file_uuid,
					'uploaderID' => $row['UploaderID']
				);
				$new_location = FileConfig::DIR_DATA . '/' . 'media' . '/' . UUIDUtils::strip($row['UploaderID']) . '/' . UUIDUtils::strip($row['ID']) . '.' . $row['Extension'];
				rename($location, $new_location);
				break;
			case 'profilepicture':
				$info = array(
					'error' => FileConfig::CONFIRM_ERRORS['OK']
				);
				$new_location = FileConfig::DIR_DATA . '/' . 'profilepicture' . '/' . UUIDUtils::strip($row['UploaderID']) . '.webp';
				rename($location, $new_location);
				break;
			case 'sticker':
				$info = array(
					'creatorID' => $row['UploaderID'],
					'error' => FileConfig::CONFIRM_ERRORS['OK'],
					'stickerID' => $file_uuid
				);
				$new_location = FileConfig::DIR_DATA . '/' . 'sticker' . '/' . UUIDUtils::strip($row['UploaderID']) . '/' . UUIDUtils::strip($row['ID']) . '.' . $row['Extension'];
				rename($location, $new_location);
				break;
			default:
				return array('error' => FileConfig::CONFIRM_ERRORS['Invalid usage']);
				break;
		}

		deleteFromUploads($file_uuid);

		return $info;
	}

	public static function deleteFile($usage, $item_uuid){
		if(in_array($usage, FileConfig::UPLOAD_USAGES)){
			$loc = FileConfig::DIR_DATA;
			switch($usage){
				case 'media':
					$loc .= generateMediaURL($item_uuid, false);
					break;
				case 'profilepicture':
					$loc .= generateProfilePictureURL($item_uuid, false);
					break;
				case 'sticker':
					$loc .= generateStickerURL($item_uuid, false);
					break;
				default:
					return;
			}
			if(file_exists($loc)){
				unlink($loc);
			}
		}	
	}

	public static function deleteFromUploads($file_uuid){
		$sql  = 'DELETE FROM Upload';
		$sql .= '  WHERE ID = :ID;';

		$params = array(':ID' => $file_uuid);

		$cmd = new SQLCommand($sql, $params);
		$cmd->execute();
	}

	public static function generateMediaURL(?string $uploaderID = NULL, string $mediaID, ?string $extension, ?bool $fileadmin = true): string{
		if(!(isset($uploaderID) && isset($extension))){
			$sql  = 'SELECT CreatorID, MediaExtension';
			$sql .= '  FROM Post';
			$sql .= '  WHERE Media = :Media;';
		
			$params = array(':Media' => $mediaID);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			if($sqlResult->isEmpty()){
				Logger::print('ERROR tried to access media with ID ' . $stickerID);
				return 'ERROR: Media not found!';
			}else{
				$row = $sqlResult->getRow();
				$uploaderID = $row['CreatorID'];
				$extension = $row['MediaExtension'];
			}
		}
		$location = '/' . ($fileadmin ? 'fileadmin/' : '') . 'media' . '/' . UUIDUtils::strip($uploaderID) . '/' . UUIDUtils::strip($mediaID) . '.' . $extension;
		return $location;
	}

	public static function generateProfilePictureURL(string $userID, ?bool $fileadmin = true): string{
		$location = '/' . 'profilepicture' . '/' . UUIDUtils::strip($userID) . '.webp';
		if(file_exists(FileConfig::DIR_DATA . $location)){
			return (($fileadmin ? '/fileadmin' : '') . $location);
		}else{
			return '/' . ($fileadmin ? 'fileadmin/' : '') . 'profilepicture' . '/' . 'default.webp';
		}
	}

	public static function generateStickerURL(?string $creatorID = NULL, string $stickerID, ?bool $fileadmin = true): string{
		if(!isset($creatorID)){
			$sql  = 'SELECT CreatorID';
			$sql .= '  FROM Sticker';
			$sql .= '  WHERE ID = :ID;';
		
			$params = array(':ID' => $stickerID);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			if($sqlResult->isEmpty()){
				Logger::print('ERROR tried to access sticker with ID ' . $stickerID);
				return '/sticker' . '/' . 'default.webp';
			}else{
				$row = $sqlResult->getRow();
				if(empty($row['CreatorID'])){
					$creatorID = UUIDUtils::getNilUUID(true);
				}else{
					$creatorID = $row['CreatorID'];
				}
			}
		}
		return '/' . ($fileadmin ? 'fileadmin/' : '') . 'sticker' . '/' . UUIDUtils::strip($creatorID) . '/' . UUIDUtils::strip($stickerID) . '.webp';
	}

}

?>