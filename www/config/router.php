<?php

class RouterConfig{
	const PUBLIC_CTRL_FILES = array(
		'land',
		'login',
		'register',
		'target'
	);

	const PRIVATE_CTRL_FILES = array(
		'create',
		'feed',
		'setting'
	);

	const ADMIN_CTRL_FILES = array(

	);

	const ALL_CTRL_FILES = array(
		'create',
		'feed',
		'land',
		'login',
		'register',
		'settings',
		'target'
	);
}

?>