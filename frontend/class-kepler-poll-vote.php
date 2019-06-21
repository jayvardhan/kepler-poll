<?php

require_once __DIR__. '/../admin/class-kepler-db-base.php';


class KEPLER_POLL_VOTE extends KEPLER_DB_BASE {
	
	function __construct() {
		
		//todo::set db tables for storing vote

		//ajax-url for voting a poll
		$this->set_ajax_handler();
		
	}


	function set_ajax_handler() {

		add_action( 'wp_ajax_'.$this->get_ajax_slug(), array( $this, 'submit_vote' ) );
		add_action( 'wp_ajax_nopriv_'.$this->get_ajax_slug(), array( $this, 'submit_vote' ) );
	}

	function get_ajax_slug(){
		return 'kepler_submit_vote';
	}

	//ajax handler for vote
	function submit_vote() {
		//todo::handle vote
		print_r($_GET);
		wp_die();
	}

}

global $kepler_vote;
$kepler_vote = KEPLER_POLL_VOTE::get_instance();

