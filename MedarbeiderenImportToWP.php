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
			add_options_page('Medarbeideren import to WP', //Title of the page
				'Medarbeideren import to WP', //Sub title of page
				 'administrator', //capability
				 __FILE__, //The file to be used
				 'med_import_to_wp_options'); //PHP Function to execute
		}
	}


	function med_import_to_wp_options() {
?>
			
		<h1>Import events from Medarbeideren to WP</h1>
		<form role="form" method="post" action="?> <?php __FILE__ ?>">
		<?php wp_nonce_field('update-options'); ?>

		 <div class="row">
		  <div class="col-sm-4">Gudstjenester</div>
		  <div class="col-sm-8"><button type="submit" id="gudstjeneste" class="btn btn-default">Start import</button></div>
		</div>
		 <div class="row">
		  <div class="col-sm-4">Jesha</div>
		  <div class="col-sm-8"><button type="submit" id="jesha" class="btn btn-default">Start import</button></div>
		</div>
		
		</form>
		<?php
	}
?>
