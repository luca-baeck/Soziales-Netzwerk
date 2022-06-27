<?php

require_once('./core/sql/sql_command.php');
require_once('./core/sql/sql_result.php');
require_once('./util/file.php');

class User{
	private ?string $uuid;
	private ?string $handle;
	private ?string $name;
	private ?DateTime $creationTime;
	private ?int $permissionLevel;
	private ?string $rank;

	public function __construct(string $uuid){
		$sql  = 'SELECT ID, Handle, Name, CreationTime';
		$sql .= '  FROM User';
		$sql .= '  WHERE ID = :ID;';

		$params = array(':ID' => $uuid);

		$cmd = new SQLCommand($sql, $params);
		$sqlResult = $cmd->execute();
		if(!$sqlResult->isEmpty()){
			$row = $sqlResult->getRow();
			$this->uuid = $row['ID'];
			$this->handle = $row['Handle'];
			$this->name = $row['Name'];
			$this->creationTime = DateTime::createFromFormat('YY-MM-DD H:i:s', $row['CreationTime']);
		}

	}

	public function getCreationTime(): DateTime{
		return $this->creationTime;
	}

	public function getHandle(): string{
		return $this->handle;
	}

	public function getName(): string{
		return $this->name;
	}

	public function getPermissionLevel(): int{
		if(!isset($this->permissionLevel)){
			$this->setPermission();
		}
		return $this->permissionLevel;
	}

	public function getProfilePictureURL(): string{
		return FileUtils::generateProfilePictureURL($this->uuid);
	}

	public function getRank(): string{
		if(!isset($this->rank)){
			$this->setPermission();
		}
		return $this->rank;
	}

	public function getUUID(): string{
		return $this->uuid;
	}

	public function isValid(): bool{
		return !empty($this->userID);
	}

	private function setPermission(){
		if(isset($this->uuid)){
			$sql  = 'SELECT P.Level, L.Name';
			$sql .= '  FROM Permission AS P, PermissionLevel AS L';
			$sql .= '  WHERE P.UserID = :UserID';
			$sql .= '    AND P.Level = L.Level;';

			$params = array(':UserID' => $this->uuid);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			if($sqlResult->isEmpty()){
				$this->permissionLevel = 1;
				$this->rank = 'user';
			}else{
				$row = $sqlResult->getRow();
				$this->permissionLevel = $row['Level'];
				$this->rank = $row['Name'];
			}
		}
	}
}

?>