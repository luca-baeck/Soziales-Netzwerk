<?php

class FileConfig{
	const DIR_LOG = '/var/log/hiddlestick';
	const DIR_DATA = '/srv/data/hiddlestick';

	const MAX_FILE_SIZE = 2097152; // in Bytes (default: 2 MiB = 2048 KiB = 2097152 B)

	const UPLOAD_TYPES = array(
		'media' => 1,
		'profilepicture' => 2,
		'sticker' => 3,
		1 => 'media',
		2 => 'profilepicture',
		3 => 'sticker'
	);

	const ALLOWED_MIME_TYPES = array(
		'media' => array(
				'image/jpeg',
				'image/png',
				'image/webp'
			),
		'profilepicture' => array(
				'image/jpeg',
				'image/png',
				'image/webp'
			),
		'sticker' => array(
				'image/png',
				'image/webp'
			)
	);

	const UPLOAD_TEXTS = array(
		'media' => 'Please select an image(*.jpg, *.png, *.webp) file to upload',
		'profilepicture' => 'Please select an image(*.jpg, *.png, *.webp) file to upload',
		'sticker' => 'Please select an image(*.png, *.webp) file to upload'
	);

	const MIME_TYPE_EXTENSIONS = array(
		'image/jpeg' => 'jpg',
		'image/png' => 'png',
		'image/webp' => 'webp'
	);
}

?>