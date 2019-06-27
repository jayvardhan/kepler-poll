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
		'admin/admin.php',
		'frontend/frontend.php',
		'frontend/class-kepler-poll-vote.php',
	);

	foreach( $inc_files as $file ) {
		require_once( $file );
	}


	function on_kepler_poll_activation() {
		flush_rewrite_rules();
	}
	register_activation_hook( __FILE__, 'on_kepler_poll_activation' );


	function on_kepler_poll_deactivation() {
		flush_rewrite_rules();
	}	
	register_deactivation_hook( __FILE__, 'on_kepler_poll_deactivation' );
