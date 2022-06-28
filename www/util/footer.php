<?php

require_once('./util/string');

class Footer{
	public static function insert($html){
		$footer_file = file_get_contents('./view/snippets/html/footer.html');
		$footer = StringUtils::stringBetweenTwoStrings($footer_file, '<!-- footer begin -->', '<!-- footer end -->');
		$styles = StringUtils::stringBetweenTwoStrings($footer_file, '<!-- footer styles start -->', '<!-- footer styles end -->');

		$html = str_replace('<!-- footer stylesheets -->', $styles, $html);
		$html = str_replace('footer placeholder', $footer, $html);
		return $html;
	}
}

?>