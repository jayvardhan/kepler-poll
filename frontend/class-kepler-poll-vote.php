<?php

require_once plugin_dir_path(__DIR__). 'admin/class-kepler-db-base.php';



class KEPLER_POLL_VOTE extends KEPLER_DB_BASE {

	function __construct() {
		
		//todo::set db tables for storing vote
		$this->set_table_slug('vote');
		parent::__construct();

		//ajax-url for voting a poll
		$this->vote_ajax_handler();
		
	}

	function get_choice_table(){
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-kepler-choice.php';
		$choice_db = KEPLER_CHOICE::get_instance();
		return $choice_db->get_table();
	}


	function create() {
		$charset_collate = $this->get_charset_collate();

		$table = $this->get_table();

		$sql = "CREATE TABLE IF NOT EXISTS $table ( 
	    			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	    			user_id bigint(20) unsigned,
	    			choice_id bigint(20) unsigned NOT NULL,
	    			vote_date DATETIME,
	 				PRIMARY KEY ( ID )
				)$charset_collate;";
		
		$this->query($sql);
		
	}


	function vote_ajax_handler() {

		add_action( 'wp_ajax_'.$this->get_ajax_slug(), array( $this, 'vote_handler' ) );
		add_action( 'wp_ajax_nopriv_'.$this->get_ajax_slug(), array( $this, 'vote_handler' ) );
	}

	function get_ajax_slug(){
		return 'kepler_submit_vote';
	}

	function is_voted( $poll_id ){
		
		$cookie_id = $this->get_cookie_id( $poll_id );
		
		if( isset($_COOKIE[$cookie_id]) ) {
			return true;
		} 
		

		$user_id = get_current_user_id();
		
		if( $user_id != 0 ) {
			global $wpdb;
			$choice_table = $this->get_choice_table();
			$result_table = $this->get_table();

			$query = "SELECT $result_table.choice_id
						FROM $result_table JOIN $choice_table
						ON $result_table.choice_id = $choice_table.ID 
						WHERE $result_table.user_id = $user_id
						AND $choice_table.poll_id = $poll_id
						";

		
			$user_choice = $wpdb->get_var($query);

			return ( $user_choice != null ) ? true : false; 
		}

		return false;
	}

	//ajax handler for vote
	function vote_handler() {

		$poll_id = $_GET['id'];

		check_ajax_referer( 'KEPLER-POLL'.$poll_id, 'nonce' );
		
		$user_id = get_current_user_id() ? get_current_user_id() : NULL;

		$choice_id = $_GET['choice'];

		$status = $this->set_vote( $poll_id, $choice_id, $user_id );

		if( $status ) { echo $this->get_poll_result( $poll_id );} 
		else { echo "<p>Vote Handler callback Error!!</p>"; }

		wp_die();
	}


	function set_vote( $poll_id, $choice_id, $user_id = NULL ){
		global $wpdb;
		$table = $this->get_table();

		$rst =	$wpdb->insert( 
					$table, 
					array( 
						'user_id'	=> $user_id, 
						'choice_id' => $choice_id,
						'vote_date' => current_time('mysql') 
					) 
				);

		if($rst) {
			$this->set_cookie( $poll_id );
		}

		return $rst;

	}


	function set_cookie( $poll_id ) {
		$cookie_id = $this->get_cookie_id( $poll_id );
		
		if( !isset( $_COOKIE[$cookie_id] ) ) {
			//set cookie for 30 days.
			setcookie( $cookie_id, "voted", ( time() + 30 * 86400 ), '/', COOKIE_DOMAIN );
	    }

	}


	function get_cookie_id( $poll_id ) {
		return 'kepler-poll-'. $poll_id;
	}

	function get_poll_result( $poll_id, $poll_user_id = 0 ) {
		global $wpdb;

		$choice_table = $this->get_choice_table();
		$result_table = $this->get_table();

		$query = "SELECT $choice_table.choice, COUNT($result_table.choice_id) * 100 / (
						SELECT COUNT(*)
						FROM $choice_table JOIN $result_table  
						ON  $choice_table.ID = $result_table.choice_id 
						WHERE $choice_table.poll_id = $poll_id
					) 
				AS percentage,
				( SELECT COUNT(*) FROM $choice_table JOIN $result_table  
					ON  $choice_table.ID = $result_table.choice_id 
					WHERE $choice_table.poll_id = $poll_id
				) 
				AS total	
				FROM $choice_table LEFT JOIN $result_table
				ON $choice_table.ID = $result_table.choice_id 
				WHERE $choice_table.poll_id = $poll_id 
				GROUP BY $choice_table.ID";

		$results = $wpdb->get_results($query);

		if($results != null){

			require_once plugin_dir_path( __FILE__ ) . 'class-kepler-poll-frontend.php';
			
			$poll_frontend = new KEPLER_POLL_FRONTEND( $poll_id );
			
			$colors = $poll_frontend->get_choice_colors();
			
			$len = count($colors);
			$i = 0;
			
			$total_responses = 0;
			
			ob_start();
				include 'partials/result-markup.php';
			return ob_get_clean();
		}
		
		return "<div>Can't retrieve results for this Poll!!</div>";

	}
}


KEPLER_POLL_VOTE::get_instance();

