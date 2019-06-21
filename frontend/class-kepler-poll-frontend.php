<?php

class KEPLER_POLL_FRONTEND {

	//type array : holds poll data 
	var $poll;

	function __construct( $id ) {
		$this->set_poll($id);
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

	function is_voted( $id ) {
		//todo: implementation

		return false;
	}


		//helper function to generate markup for poll choice
	function get_choice_markup($poll_id) {
		$poll = $this->get_poll();
		//var_dump($poll);
		$choices = $poll['poll_choices'];

		if( is_array($choices) && count($choices) ) {

			require_once 'class-kepler-poll-vote.php';
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
				$voted =  $this->is_voted( $poll['ID'] );
				
				if( !$voted ){
					// get poll choice
					$poll_choice_markup .= $this->get_choice_markup( $poll['ID'] );	

				} else { 
					/* IF VOTED THEN SHOW ONLY THE RESULTS */
					
					echo "Provide implementation for poll result";
				}

				include 'partials/poll-html.php';

			
		
		} else {
			echo "Invalid Poll";
		}

		return ob_get_clean();
	}

}