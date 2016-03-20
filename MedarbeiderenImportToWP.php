<?php
/*
Plugin Name: Medarbeideren import to WP
Plugin URI: http://about:none/
Description: Plugin which imports events from Medarbeideren to WP
Version: 0.1
Author: Knut Erik Hollund
Author URI: http://about:none/
License: GPLv3
*/
	require_once( dirname(__FILE__) . '/medarbeideren-import.php' );

	/* Runs when plugin is activated */
	register_activation_hook(__FILE__,'med_import_to_wp_install'); 

	/* Runs on plugin deactivation*/
	register_deactivation_hook( __FILE__, 'med_import_to_wp_remove' );

	function med_import_to_wp_install() {
	/* Creates new database field */
		//add_option('wp_exec_cmd_data', 'Default', '', 'yes');
	}

	function med_import_to_wp_remove() {
	/* Deletes the database field */
	//	delete_option('wp_exec_cmd_data');
	}

	if (is_admin() ){				

		/* Call the code */
		add_action('admin_menu', 'med_import_to_wp_admin_menu');		
		function med_import_to_wp_admin_menu() {			
			add_utility_page( 'Medarbeideren import', 'Medarbeideren import', 'administrator', 'medarbeideren-import','showHTML');
		}		
	}	
	
	function myplugin_settings_admininit() {
		//register_setting( 'myplugin', 'myplugin_setting_1', 'intval' );
		//register_setting( 'myplugin', 'myplugin_setting_2', 'intval' );
	}
	add_action( 'admin_init', 'myplugin_settings_admininit' );	
?>
