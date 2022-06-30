<?php

require_once('./config/files.php');
require_once('./core/logger.php');
require_once('./core/sql/sql_command.php');
require_once('./util/uuid.php');

class FileUtils{

	public static function insertUploadArea($html, $usage){
		$upload_file = file_get_contents('./view/snippets/html/uploadwrapper.html');
		$upload_wrapper = StringUtils::stringBetweenTwoStrings($upload_file, '<!-- upload wrapper begin -->', '<!-- upload wrapper end -->');
		$styles = StringUtils::stringBetweenTwoStrings($upload_file, '<!-- upload wrapper styles begin -->', '<!-- upload wrapper styles end -->');

		$html = str_replace('<!-- upload wrapper stylesheets -->', $styles, $html);
		$html = str_replace('<!-- upload wrapper placeholder -->', $upload_wrapper, $html);
		return $html;
	}

	public static function generateMediaURL(?string $uploaderID = NULL, string $mediaID, ?string $extension): string{
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
		$location = '/' . 'media' . '/' . UUIDUtils::strip($uploaderID) . '/' . UUIDUtils::strip($mediaID) . '.' . $extension;
		return $location;
	}

	public static function generateProfilePictureURL(string $userID): string{
		$location = '/' . 'profilepicture' . '/' . UUIDUtils::strip($userID) . '.webp';
		if(file_exists(FileConfig::DIR_DATA . $location)){
			return $location;
		}else{
			return '/' . 'profilepicture' . '/' . 'default.webp';
		}
	}

	public static function generateStickerURL(?string $creatorID = NULL, string $stickerID): string{
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
		return '/sticker' . '/' . UUIDUtils::strip($creatorID) . '/' . UUIDUtils::strip($stickerID) . '.webp';
	}

}

?>