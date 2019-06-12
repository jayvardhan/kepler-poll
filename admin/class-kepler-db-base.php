<?php

class KEPLER_DB_BASE extends KEPLER_BASE {
	protected $table;
	protected $table_slug;

	function __construct() {
		$this->set_table( $this->get_table_prefix().$this->get_table_slug() );
		$this->create();	
	}

	function set_table_slug( $slug ){
		$this->table_slug = $slug;
	}

	function get_table_slug() {
		return $this->table_slug;
	}

	function get_table_prefix() {
		global $wpdb;

		return $wpdb->prefix . 'kepler_';
	}

	function set_table( $table ){
		$this->table = $table;
	}

	function get_table(){
		return $this->table;
	}

	function create() {
		//to be implemented by child class for creating database tables
	}

}