<?php

class KEPLER_CHOICE extends KEPLER_DB_BASE {
	
	
	function __construct() {
		$this->set_table_slug('choice');
		parent::__construct();
	}

	
	function create() {
		$charset_collate = $this->get_charset_collate();

		$table = $this->get_table();

		$sql = "CREATE TABLE IF NOT EXISTS $table ( 
	    			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	    			poll_id bigint(20) unsigned NOT NULL,
					choice varchar(255),
					PRIMARY KEY ( ID )
				)$charset_collate;";
		
		$this->query($sql);
		
	}

	function get_choices( $poll_id, $output_type = OBJECT ) {
		global $wpdb;
		$table = $this->get_table();

		if($poll_id) {
			$query = "SELECT ID, choice FROM $table WHERE poll_id=$poll_id";

			return $wpdb->get_results($query, $output_type);

		}

	}

	//update existing choice
	function update( $choice ) {
		global $wpdb;
		$table = $this->get_table();
		
		if($choice['title'] != ''){
			$wpdb->update( 
				$table, 
				array( 'choice' => $choice['title']), 
				array( 'ID' => $choice['id'] ) 
			);	
		}
		
	}

	//insert choices in db table; accepts array
	function insert( $poll_id, $choice ) {
		if( $choice['title'] != '' ) {
			global $wpdb;
			$table = $this->get_table();

			$wpdb->insert(
				$table,
				array(
					'poll_id'	=> $poll_id,
					'choice'	=> $choice['title']	
				),
				array( '%d', '%s' )
			);
		}	
	}

	//accepts array
	function delete( $choice_ids ) {
		if( is_array($choice_ids) ) {
			global $wpdb;
			$table = $this->get_table();
			$ids_str = implode( ',', $choice_ids );
			$query = "DELETE FROM $table WHERE ID IN ($ids_str);";
			$wpdb->query( $query );
		}
	}

	function sanitize( $data ){
				
		if( is_array($data) ) {
			
			$sanitized_data = array();
			
			foreach($data as $data_item){
				if(is_array($data_item)){
					$sanitized_data[] = array(
						'title' => sanitize_text_field( $data_item['title'] ),
						'id'	=> absint( $data_item['id'] ),
					);	
				}
			}
			return $sanitized_data;
		}

		

	}

} //end of class

