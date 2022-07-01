<?php

require_once('./data/post.php');
require_once('./data/user.php');
require_once('./util/string.php');

class ElementUtils{

	public static function insertElement(string $uuid, $html, ?bool $allowFullscreen = true, ?User $user = NULL): string{
		$post = new Post($uuid);
		if(!isset($user)){
			$user = new User($post->getCreatorUUID());
		}
		$element_file = file_get_contents('./view/snippets/html/element.html');
		$element = StringUtils::stringBetweenTwoStrings($element_file, '<!-- element begin -->', '<!-- element end -->');

		$element = str_replace('sticker-src', $post->getStickerURL(), $element);
		$element = str_replace('<!-- post content -->', $post->getContent(), $element);
		$element = str_replace('creator-handle', $user->getHandle(), $element);
		$element = str_replace('profile-picture-src', $user->getProfilePictureURL(), $element);
		$element = str_replace('<!-- creator name -->', $user->getName(), $element);
		$element = str_replace('<!-- creator handle -->', $user->getHandle(), $element);
		$element = str_replace('<!-- creation date -->', $post->getCreationTime()->format('Y.m.d'), $element);

		if($post->hasMedia()){
			$element = str_replace('<!-- media location -->', '<img src="' . $post->getMediaURL() . '" class="element-media-img" loading="lazy">', $element);
		}

		$html = str_replace('<!-- element placeholder -->', $element, $html);
		return $html;
	}

	public static function insertElementStyles($html): string{
		if(!isset($element_file)){
			$element_file = file_get_contents('./view/snippets/html/element.html');
		}
		$styles = StringUtils::stringBetweenTwoStrings($element_file, '<!-- element styles begin -->', '<!-- element styles end -->');

		$overlay = StringUtils::stringBetweenTwoStrings($element_file, '<!-- overlay begin -->', '<!-- overlay end -->');

		$html = str_replace('<!-- overlay placeholder -->', $overlay, $html);

		$html = str_replace('<!-- element stylesheets -->', $styles, $html);
		return $html;
	}

}

?>