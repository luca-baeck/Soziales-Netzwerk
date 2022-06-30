<?php

class Miscellaneous{
	const RANKS = array(
		'admin',
		'user',
		'guest'
	);

	const RANK_LEVELS = array(
		'admin' => 255,
		'user'  => 1,
		'guest' => 0
	);

	const LEVEL_RANKS = array(
		255 => 'admin',
		1   => 'user',
		0   => 'guest'
	);
}

?>