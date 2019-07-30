<?php

class KEPLER_FRONTEND {
	
	function __construct() {

		add_filter( 'the_content', array( $this, 'the_content_cb' ) );
		
		add_shortcode('yka_polls', array($this, 'poll_html'));

		add_shortcode( 'kepler_trending_polls', array( $this, 'trending_polls_cb' ) );

	}


	function assets() {
		$plugin_assets_folder = "kepler-poll/assets/";

		
	    wp_enqueue_style(
	    	'kepler-poll-css',
	    	plugins_url( $plugin_assets_folder. 'css/frontend.css' ),
	    	array(), 
	    	KEPLER_POLL_VERSION
	    );

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

		$this->assets();

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

	function trending_polls_cb( $atts ) {

		$default_atts = array(
				'title'				=> 'Trending Polls :',
				'posts_per_page' 	=> '3',
				'offset'			=> '0',
				'load_more'			=> '0',
				'paged'				=> '1',
				'grid'				=> 'card',
				'mobile_grid'		=> '0',
				'grid_cols'			=> '0',
				'id'				=> 'story-grid-list',
				'orderby'			=> 'latest',
				'cache_hour'		=> '8',
			);

		$atts = shortcode_atts($default_atts, $atts);
		
		$polls = $this->trending_poll_query_result($atts);

		
		if($polls) {

			//echo "<pre>"; print_r($polls);echo "</pre>";

			foreach ($polls as $poll ) {
				echo $this->poll_html( array( "id" => $poll->poll_id ) );

			}

			 

		} else {
			//most likely if polls doesn't have any votes yet, toss this message 
			echo "<span class='btn-danger'>Try using orderby='latest' as parameter!</span>";
		}

	}


	function trending_poll_query_result($atts){
		global $wpdb;
		$data = false;

		$limit = $atts['posts_per_page'];
	

		$choice_db 	= KEPLER_CHOICE::get_instance();
		$vote_db 	= KEPLER_POLL_VOTE::get_instance();
			

		//$key_params =  array($limit, $atts['cache_hour'], $atts['orderby'], $atts['offset']);
		
		//$transient_key = 'yka_trending_polls_'. $this->get_unique_id($key_params);
		
		//$data = get_transient( $transient_key );

		if( $data === false ) {
			

			$choice_tbl = $choice_db->get_table();
			$vote_tbl = $vote_db->get_table();

			//retrieval is based on number of votes
			$query = "SELECT $choice_tbl.poll_id , COUNT(*) AS votes FROM $choice_tbl JOIN $vote_tbl ON $choice_tbl.ID = $vote_tbl.choice_id GROUP BY $choice_tbl.poll_id ORDER BY votes DESC LIMIT $limit";


			//set offset if passed from shortcode
			if( isset($atts['offset']) && ( $atts['offset']) > 0 ) {
				$query = $query. " OFFSET ". $atts['offset'];
			}

			$data = $wpdb->get_results( $query );	


			//$cache_time = ((int) $atts['cache_hour']) * HOUR_IN_SECONDS;
			//set_transient( $transient_key, $data, $cache_time );

		}


		return $data;


	}


	
}

global $kepler_frontend;

$kepler_frontend = new KEPLER_FRONTEND();
