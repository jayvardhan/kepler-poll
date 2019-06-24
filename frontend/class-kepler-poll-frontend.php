<?php

require_once plugin_dir_path(__DIR__) . 'class-kepler-base.php';
require_once plugin_dir_path(__FILE__). 'class-kepler-poll-vote.php';


class KEPLER_POLL_FRONTEND extends KEPLER_BASE {

	/*type array : holds poll data */
	var $poll; 

	/*type array: holds colors for choice options*/
	var $colors = array('#ffe981', '#c2f5d9', '#82dffc', '#f5cfbf'); 

	
	function __construct( $id ) {
		$this->set_poll($id);
		$this->set_choice_colors();
	}

	
	function get_poll() {
		return $this->poll;
	}

	//prepares poll array by pulling data from db
	function set_poll( $id ) {

		//retrieve poll object and cast into array
		$poll_data = (array) get_post($id);

		//retrieve poll_end date and add to poll_data 
		$poll_data['poll_end_date'] = $this->get_poll_end_date($id);

		//retrieve poll choices and add to poll_data
		$poll_data['poll_choices'] = $this->get_poll_choices($id);

		$this->poll = $poll_data;
	}

	function get_poll_choices($id) {
		
		$choice_db = KEPLER_CHOICE::get_instance();
		$choice_list = $choice_db->get_choices( $id, ARRAY_A );

		return $choice_list;

	}

	function get_poll_end_date( $id ) {
		return get_post_meta( $id, '_kepler_end_date', true );
	}

	function get_choice_colors() {
		return $this->colors;
	}

	function set_choice_colors( $colors = null ) {
		if( is_array($colors) && count($colors) ) {
			$this->colors = $colors;
		}
	}



	function is_voted( $id ) {

		$kepler_vote = KEPLER_POLL_VOTE::get_instance();
		return $kepler_vote->is_voted( $id );
		
	}


	//helper function to generate markup for poll choice
	function get_choice_markup($poll_id) {
		$poll = $this->get_poll();
		$choices = $poll['poll_choices'];

		if( is_array($choices) && count($choices) ) {

			$vote_obj = KEPLER_POLL_VOTE::get_instance();

			$ajax_url	= admin_url('admin-ajax.php').'?action='. $vote_obj->get_ajax_slug();
			$nonce		= wp_create_nonce('KEPLER-POLL'.$poll['ID']);

			ob_start();
			
				include 'partials/choice-markup.php';
			
			return ob_get_clean();

		} else { 

			return "<div>Choice Doesn't Exists For this Poll!!</div>";
		} 
		
	}


	function html() {

		ob_start();
		$poll = $this->get_poll();

		if( $poll['ID'] > 0 ) {
			
			$poll_choice_markup = "";
			
				//check if poll is already voted by user
				$kepler_vote = KEPLER_POLL_VOTE::get_instance();

				$voted =  $kepler_vote->is_voted( $poll['ID'] );
				
				if( !$voted ){
					// get poll choice
					$poll_choice_markup .= $this->get_choice_markup( $poll['ID'] );	

				} else { 

					$poll_user_id = 0;
					if( isset( $poll['post_author'] ) ){ $poll_user_id = $poll['post_author']; }
					
					$poll_choice_markup = $kepler_vote->get_poll_result( $poll['ID'], $poll_user_id );
				}

				include 'partials/poll-html.php';
		
		} else {
			echo "Invalid Poll";
		}

		return ob_get_clean();
	}

}