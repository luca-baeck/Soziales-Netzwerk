<?php

require_once('./util/string.php');

class Icons{
	public static function insert(string $html): string{
		$icons_file = file_get_contents('./view/snippets/html/icons.html');
		$icons = StringUtils::stringBetweenTwoStrings($icons_file, '<!-- icons begin -->', '<!-- icons end -->');

		$html = str_replace('<!-- icons placeholder -->', $icons, $html);
		return $html;
	}
}

?>