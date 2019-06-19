<?php

class KEPLER_ADMIN {

	
	function __construct() {

		//register poll as post type
		add_action( 'init', array( $this, 'create_post_type' ) );
		
		//update add new title placeholeder to add poll question
		add_filter( 'enter_title_here', array($this, 'add_poll_placeholder') , 20 , 2 );

		//add metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		//save end date metabox
		add_action( 'save_post', array( $this, 'persist_meta_box_data') );

		//enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	
	
	function assets() {

		$plugin_assets_folder = "kepler-poll/assets/";

		//jQuery UI date picker file
	    wp_enqueue_script('jquery-ui-datepicker');
	    
	    //jQuery UI theme css file
	    wp_enqueue_style('kepler-jquery-ui-css','https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css',false,"1.12.1",false);

	    wp_enqueue_style(
	    	'kepler-admin-css',
	    	plugins_url( $plugin_assets_folder. 'css/admin.css' ),
	    	array(), 
	    	KEPLER_POLL_VERSION
	    );

	    //admin js file
	    wp_enqueue_script(
			'kepler-admin-js',
			plugins_url( $plugin_assets_folder.'js/admin.js' ),
			array( 'jquery'),
			KEPLER_POLL_VERSION,
			true
		);
	}
	

	function create_post_type() {
		
		$args = array(
			'slug'		=> 'kepler_poll',
			'labels' 	=> array(
								'name' 			=> 'Kepler Polls',
								'singular_name' => 'Kepler Poll',
								'add_new_item'	=> 'Add New Poll',
							),
			'supports'	=> array( 'title','author' ),
			'menu_icon'	=> 'dashicons-megaphone'
		);

		register_post_type( $args['slug'],
			array(
				'labels' 				=> $args['labels'],
				'public' 				=> isset( $args['public'] ) ? $args['public'] : true,
				'publicly_queryable' 	=> true,
				'show_ui'				=> true,
				'query_var' 			=> true,
				'has_archive' 			=> true,
				'menu_icon'				=> isset( $args['menu_icon'] ) ? $args['menu_icon'] : 'dashicons-images-alt',
				'supports'				=>	$args['supports']
			)
		);
	}

	function add_poll_placeholder( $title , $post ){
		
		if( $post->post_type == 'kepler_poll' ){
            $title = "Add Poll Question";
        }

        return $title;
	}


	function add_meta_boxes(){

		//intialize meta box variables
		$meta_boxes = array(
			array(
				'id'		=> 'kepler-end-date',
				'title'		=> 'End Date for Poll',
				'box_html'	=> 'end_date_mb_html',
			),
			array(
				'id'		=> 'kepler-poll-choices',
				'title'		=> 'Add Poll Choices',
				'box_html'	=> 'poll_choices_mb_html',
				'context'	=> 'normal',
				'priority'	=> 'high',
			),
		);
		
		

		// REGISTER META BOXES
		foreach( $meta_boxes as $meta_box ){

			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				array( $this, $meta_box['box_html'] ),
				'kepler_poll',
				isset( $meta_box['context'] ) ? $meta_box['context'] : 'side',
				isset( $meta_box['priority'] ) ? $meta_box['priority'] : 'default',
				$meta_box
			);
		}
	}


	function end_date_mb_html( $post, $metabox ){

		include( 'templates/metabox-'. $metabox['id'] .'.php' );
	}

	function poll_choices_mb_html( $post, $metabox ) {
		
		include( 'templates/metabox-'. $metabox['id'] .'.php' );
	}



	function persist_meta_box_data( $poll_id ) {
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }

	    // Check the user's permissions.
	    if ( ! (isset( $_POST['post_type'] ) && 'kepler_poll' == $_POST['post_type'] ) || ! current_user_can( 'edit_page', $poll_id )) {
	    	return;
	    }

	    $form_data_keys = array(
	    	"end_date" 		=> "_kepler_end_date",
	    	"choice_items" 	=> "_kepler_poll_choice",
	    	"delete_items" 	=> "_kepler_choice_delete",	
	    );

	    $this->sanitize_and_save( $poll_id, $form_data_keys);

	}

	function sanitize_and_save( $poll_id, $keys ){
					
		if(count($keys)){
			
			foreach ($keys as $type => $key) {
						
				if( ! isset( $_POST[$key] ) ){
					return;
				} 

				//save end-date
				if( 'end_date' == $type ) {
					
					$data = sanitize_text_field( $_POST[$key] );
					update_post_meta( $poll_id, $key, $data );
				
				}

				//save choices
				if( 'choice_items' == $type ) {
					$poll_choices = $_POST[$key];
					
					if( is_array($poll_choices) && count($poll_choices) ) {
						
						//require_once('class-kepler-choice.php');
						$choice_db = KEPLER_CHOICE::get_instance();
						
						$poll_choices = $choice_db->sanitize($poll_choices);
						
						foreach ($poll_choices as $poll_choice) {
							
							if($poll_choice['id']) {
								
								$choice_db->update( $poll_choice );

							} else if( strlen($poll_choice['title']) ) {
								
								$choice_db->insert( $poll_id, $poll_choice );
							}
						}

					}
					
				}

				//delete choice
				if( 'delete_items' == $type ){

					$delete_ids = $_POST[$key];
					
					if(strlen($delete_ids)){
						$choice_ids = array_map( 'trim', explode(',', $delete_ids));
						$choice_db->delete( $choice_ids );
					}
				} 
			}
		}
	    
	}


} //end of class

new KEPLER_ADMIN();