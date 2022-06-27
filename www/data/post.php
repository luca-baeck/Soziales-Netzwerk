<?php

require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./util/file.php');

class Post{
	private ?string $uuid;
	private ?string $creatorUUID;
	private ?string $stickerUUID;
	private ?string $mediaUUID;
	private ?string $mediaExtension;
	private ?string $content;
	private ?DateTime $creationTime;
	private ?int $points;
	
	public function __construct(string $uuid){
		if(isset($uuid)){
			$sql  = 'SELECT DISTINCT P.ID, P.CreatorID, P.StickerID, P.Media, P.MediaExtension, P.Content, P.CreationTime, COUNT(I.UserID) AS Points';
			$sql .= '  FROM Post AS P, Point AS I';
			$sql .= '  WHERE P.ID = :ID';
			$sql .= '    AND P.ID = I.ID';
			$sql .= '  GROUP BY I.ID;';

			$params = array(':ID' => $uuid);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			if(!$sqlResult->isEmpty()){
				$row = $sqlResult->getRow();
				$this->uuid = $row['ID'];
				$this->creatorUUID = $row['CreatorID'];
				if(isset($row['StickerID'])){
					$this->stickerUUID = $row['StickerID'];
				}
				if(isset($row['Media'])){
					$this->mediaUUID = $row['Media'];
					$this->mediaExtension = $row['MediaExtension'];
				}
				$this->content = $row['Content'];
				$this->creationTime = DateTime::createFromFormat('YY-MM-DD H:i:s', $row['CreationTime']);
				$this->points = $row['Points'];
			}
		}
	}

	public function getContent(): string{
		return $this->content;
	}

	public function getCreationTime(): DateTime{
		return $this->creationTime;
	}

	public function getCreatorUUID(): string{
		return $this->creatorUUID;
	}

	public function getMediaExtension(): string{
		return $this->mediaExtension;
	}

	public function getMediaURL(): string{
		return FileUtils::generateMediaURL($this->creatorUUID, $this->mediaUUID, $this->mediaExtension);
	}

	public function getMediaUUID(): string{
		return $this->mediaUUID;
	}

	public function getPoints(): int{
		return $this->points;
	}

	public function getStickerURL(): string{
		return FileUtils::generateStickerURL($this->stickerUUID);
	}

	public function getStickerUUID(): string{
		return $this->stickerUUID;
	}

	public function getUUID(): string{
		return $this->uuid;
	}

	public function gotPoint(string $userUUID): bool{
		if(isset($userUUID)){
			$sql  = 'SELECT Time';
			$sql .= '  FROM Point';
			$sql .= '  WHERE PostID = :PostID';
			$sql .= '    AND UserID = :UserID;';

			$params = array(':PostID' => $this->uuid, ':UserID' => $us<erUUID);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();

			return !$sqlResult->isEmpty();
		}
	}

	public function hasMedia(): bool{
		return isset($this->mediaUUID);
	}

	public function hasSticker(): bool{
		return isset($this->stickerUUID);
	}

	public function isValid(): bool{
		return isset($this->uuid);
	}

}

?>