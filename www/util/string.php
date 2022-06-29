<?php

class StringUtils{

	public static function stringBetweenTwoStrings($str, $starting_word, $ending_word){
		$substring_start = strpos($str, $starting_word);
		$substring_start += strlen($starting_word);
		$size = strpos($str, $ending_word, $substring_start) - $substring_start;
		return substr($str, $substring_start, $size);
	}

	public static function stringStartsWith(string $haystack, string $needle): bool{
		return substr_compare($haystack, $needle, 0, strlen($needle)) == 0;
	}
	
}

?>