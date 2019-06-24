<?php

class KEPLER_FRONTEND {
	
	function __construct() {

		add_filter( 'the_content', array( $this, 'the_content_cb' ) );
		
		add_shortcode('kepler_poll', array($this, 'poll_html'));

		//enqueue scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

	}


	function assets() {
		$plugin_assets_folder = "kepler-poll/assets/";

		
	    wp_enqueue_style(
	    	'kepler-poll-css',
	    	plugins_url( $plugin_assets_folder. 'css/frontend.css' ),
	    	array(), 
	    	KEPLER_POLL_VERSION
	    );

	    //admin js file
	    wp_enqueue_script(
			'kepler-poll-js',
			plugins_url( $plugin_assets_folder.'js/frontend.js' ),
			array( 'jquery'),
			KEPLER_POLL_VERSION,
			true
		);
	}
	
	// kepler_poll shortcode callback
	function poll_html( $atts ) {
		$poll_id = $atts['id'];

		require_once plugin_dir_path( __FILE__ ) . 'class-kepler-poll-frontend.php';
		$poll_frontend = new KEPLER_POLL_FRONTEND( $poll_id );
		return $poll_frontend->html();
	}

	function the_content_cb($content) {

		global $post;

		if( $post->post_type == 'kepler_poll' ){
			$content = $this->poll_html( array('id'=> $post->ID) );
		}

		return $content;
	}

	
}

global $kepler_frontend;

$kepler_frontend = new KEPLER_FRONTEND();
