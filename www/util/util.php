<?php

function stringBetweenTwoStrings($str, $starting_word, $ending_word){
	$substring_start = strpos($str, $starting_word);
	$substring_start += strlen($starting_word);
	$size = strpos($str, $ending_word, $substring_start) - $substring_start;
	return substr($str, $substring_start, $size);
}

?>