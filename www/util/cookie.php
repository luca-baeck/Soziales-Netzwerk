<?php

	function deleteCookie(string $name, ?string $location){
		if(isset($_COOKIE[$name])){
			unset($_COOKIE[$name]);
			if(empty($location)){
				setcookie($name, null, -1);
			}else{
				setcookie($name, null, -1, $location); 
			}
			return true;
		}else{
			return false;
		}
	}

	function deleteCookies(array $names, ?string $location){
		foreach($names as $current){
			deleteCookie($current, $location);
		}
	}

?>