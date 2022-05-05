<?php

function stringBetweenTwoStrings($str, $starting_word, $ending_word){
	$substring_start = strpos($str, $starting_word);
	$substring_start += strlen($starting_word);
	$size = strpos($str, $ending_word, $substring_start) - $substring_start;
	return substr($str, $substring_start, $size);
}

function insertHeader($html){
	$header_file = file_get_contents(__DIR__ . './static/header.html');
	$header = '<header>';
	$header .= stringBetweenTwoStrings($header_file, '<header>', '</header>');
	$header .= '</header>';

	$header_file = file_get_contents(__DIR__ . './static/header.html');
	$styles = stringBetweenTwoStrings($header_file, '<!-- header styles start -->', '<!-- header styles end -->');

	$html = str_replace('<!-- header stylesheets -->', $styles, $html);
	$html = str_replace('<header></header>', $header, $html);
	return $html;
}

?>