<?php

	function createCookie(string $name, $value, int $timeToExpire, ?string $location = NULL){
		if(empty($location)){
			setcookie($name, $value, $timeToExpire, '/');
		}else{
			setcookie($name, $value, $timeToExpire, $location);
		}
	}

	function deleteCookie(string $name, ?string $location = NULL){
		if(isset($_COOKIE[$name])){
			unset($_COOKIE[$name]);
			if(empty($location)){
				setcookie($name, null, -1, '/');
			}else{
				setcookie($name, null, -1, $location); 
			}
			return true;
		}else{
			return false;
		}
	}

	function deleteCookies(array $names, ?string $location = NULL){
		foreach($names as $current){
			deleteCookie($current, $location);
		}
	}

?>