<?php

class FileConfig{
	const DIR_LOG = '/var/log/hiddlestick';
	const DIR_DATA = '/srv/data/hiddlestick';

	const MAX_FILE_SIZE = 2097152; // in Bytes (default: 2 MiB = 2048 KiB = 2097152 B)

	const MEDIA_MIME_TYPES = array(
		'image/jpeg',
		'image/png',
		'image/webp'
	);

	const PROFILEPICTURE_MIME_TYPES = array(
		'image/jpeg',
		'image/png',
		'image/webp'
	);

	const STICKER_MIME_TYPES = array(
		'image/png',
		'image/webp'
	);

	const UPLOAD_TYPES = array(
		'media' => 1,
		'profilepicture' => 2,
		'sticker' => 3
	);
}

?>