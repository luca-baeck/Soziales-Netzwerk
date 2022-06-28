<?php

class UUIDUtils{

	public static function expand(string $uuid) :string{
		if(empty($uuid)){
			return 'ERROR: empty UUID';
		}else if(strlen($uuid) == 36){
			return $uuid;
		}else if(strlen($uuid) != 32){
			return 'ERROR: wrong format';
		}else{
			return substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
		}
	}

	public static function getNilUUID(?bool $short = false): string{
		if($short){
			return '00000000000000000000000000000000';
		}else{
			return '00000000-0000-0000-0000-000000000000';
		}
	}

	public static function strip(string $uuid): string{
		if(empty($uuid)){
			return 'ERROR: empty UUID';
		}else if(strlen($uuid) == 32){
			return $uuid;
		}else if(strlen($uuid) != 36){
			return 'ERROR: wrong format';
		}else{
			return substr($uuid, 0, 8) . substr($uuid, 9, 4) . substr($uuid, 14, 4) . substr($uuid, 19, 4) . substr($uuid, 24, 12);
		}
	}

}
	
?>