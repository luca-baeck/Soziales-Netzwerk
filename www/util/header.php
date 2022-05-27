<?php

require_once('./util/string.php');

function insertHeader($html){
	$header_file = file_get_contents('./static/header.html');
	$header = '<header>';
	$header .= StringUtils::stringBetweenTwoStrings($header_file, '<header>', '</header>');
	$header .= '</header>';
	$styles = StringUtils::stringBetweenTwoStrings($header_file, '<!-- header styles start -->', '<!-- header styles end -->');

	$html = str_replace('<!-- header stylesheets -->', $styles, $html);
	$html = str_replace('<header></header>', $header, $html);
	return $html;
}

?>