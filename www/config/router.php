<?php

class RouterConfig{
	const PUBLIC_CTRL_FILES = array(
		'fileadmin',
		'land',
		'login',
		'register',
		'target'
	);

	const PRIVATE_CTRL_FILES = array(
		'create',
		'feed',
		'settings'
	);

	const ADMIN_CTRL_FILES = array(

	);

	const ALL_CTRL_FILES = array(
		'create',
		'feed',
		'fileadmin',
		'land',
		'login',
		'register',
		'settings',
		'target'
	);
}

?>