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
			return substr($uuid, 0, 8) . '-' . substr($uuid, 8, 12) . '-' . substr($uuid, 12, 16) . '-' . substr($uuid, 16, 20) . '-' . substr($uuid, 20, 32);
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
			return substr($uuid, 0, 8) . substr($uuid, 9, 13) . substr($uuid, 14, 18) . substr($uuid, 19, 23) . substr($uuid, 24, 36);
		}
	}

}
	
?>