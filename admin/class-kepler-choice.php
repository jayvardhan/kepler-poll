<?php

class KEPLER_CHOICE extends KEPLER_DB_BASE {
	
	
	function __construct() {
		$this->set_table_slug('choice');
		parent::__construct();
	}

	
	function create() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table = $this->get_table();

		$sql = "CREATE TABLE IF NOT EXISTS $table ( 
	    			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	    			poll_id bigint(20) unsigned NOT NULL,
					choice varchar(255),
					PRIMARY KEY ( ID )
				)$charset_collate;";
		
		$wpdb->query( $sql );

	}

} //end of class

$choice_db = KEPLER_CHOICE::get_instance();