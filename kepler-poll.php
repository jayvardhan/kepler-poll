<?php

	/*
		Plugin Name: Kepler Poll
		Plugin URI: https://sputznik.com/
		Description: A Poll Plugin.
		Version: 1.0.0
		Author: Jay Vardhan
		Author URI: https://sputznik.com/
	*/

	if( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	//constant to change js and css version
	define( 'KEPLER_POLL_VERSION', '1.0.0' );

	$inc_files = array(
		'class-kepler-base.php',
		'admin/class-kepler-admin.php',
	);

	foreach( $inc_files as $file ) {
		require_once( $file );
	}

