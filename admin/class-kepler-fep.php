<?php

class KEPLER_FEP extends KEPLER_BASE {

	function __construct() {
		add_shortcode( 'kepler_fep_form', array( $this, 'fep_form_cb' )); 

		add_action( 'wp_ajax_kepler_poll_fep', array( $this, 'fep_ajax_cb') );

	}

	function assets() {
		$plugin_assets_folder = "kepler-poll/assets/";

		wp_enqueue_style(
	    	'kepler-poll-fep-css',
	    	plugins_url( $plugin_assets_folder. 'css/fep.css' ),
	    	array(), 
	    	KEPLER_POLL_VERSION
	    );

		wp_enqueue_script(
			'kepler-poll-fep-js',
			plugins_url( $plugin_assets_folder.'js/fep.js' ),
			array( 'jquery'),
			KEPLER_POLL_VERSION,
			true
		);
	}

	function fep_form_cb() {
		$url	= admin_url('admin-ajax.php').'?action=kepler_poll_fep';
		$token	= wp_create_nonce('KEPLER-POLL-FEP');

		$this->assets();

		ob_start();
			include 'templates/fep-form.php';
		return ob_get_clean();
	}

	function fep_ajax_cb() {
		check_ajax_referer( 'KEPLER-POLL-FEP', 'token' );
		
		$shortcode = $this->create_poll( $_POST['question'], $_POST['choices'] );

		echo $shortcode;

		wp_die();
	}

	function create_poll( $question, $choices ) {
		$question = sanitize_text_field($question);

		$args = array(
			'post_title' => $question,
			'post_status' => 'publish',
			'post_type'	=> 'kepler_poll'
		);

		// insert the poll cpt into the database
		$poll_id = wp_insert_post( $args);

		$choice_db = KEPLER_CHOICE::get_instance();
		$choices = $choice_db->sanitize($choices);	
		
		
		// insert choices into table
		if( is_array($choices) && count($choices) ) {
			
			foreach ($choices as $choice) {

				if( strlen($choice['title']) ) {

					$choice_db->insert( $poll_id, $choice );
				}
			}

		}

		return "[kepler_poll id=$poll_id]";
	}
}

new KEPLER_FEP;