<?php
/*
Plugin Name: Freya.Lt Buhalterija
Description: Buhalterija
Author: Donatas Vasiliauskas
Version: 1.0.0
*/	
    add_action('admin_menu', 'buhalterija_plugin_setup_menu');
    add_action( 'parse_query', 'buhalterija_global' );

   

	 
	function buhalterija_plugin_setup_menu() {
	    add_menu_page( 'Buhalterija', 'Buhalterija', 'manage_options', 'freya-buhalterija', 'buhalterija_init',
			'dashicons-schedule', 3 );
	}
	 
	function buhalterija_init(){
		require plugin_dir_path(__FILE__).'view/class_buhalterija_View_Construct.php';
		require plugin_dir_path(__FILE__).'functions/class_buhalterija_functions.php';
		require plugin_dir_path(__FILE__).'functions/class_create_db_functions.php';
		require plugin_dir_path(__FILE__).'functions/class_db_functions.php';
		new class_create_db_functions();
		global $classDo, $classDB, $buhalterija_settings;
		$classDB = new class_db_functions();
		$classDo = new class_buhalterija_functions();
		$view = new class_buhalterija_View_Construct();
		$settings = $classDB->get_Results('buhalterija_global_settings'); 
		$buhalterija_settings = $settings[0];
		get_psl($view);
		$view->bottom();
		if (empty($buhalterija_settings->items_per_page)) {
			global $wpdb;
			$wpdb->insert($wpdb->prefix.'buhalterija_global_settings', array('items_per_page' => 10), array('%d'));
		}
	}



	function buhalterija_global() {
		global $buhalterija_settings, $classDB, $classDo;
	}

	function get_psl($view) {
		if (isset($_GET['psl']) && ((int) $_GET['psl']) == 1) {
			$view->get_OZ_View();
		} elseif (isset($_GET['psl']) && ((int) $_GET['psl']) == 2) {
		  $view->get_IS_View();
		} elseif (isset($_GET['psl']) && ((int) $_GET['psl']) == 3) {
		  $view->get_PJ_View();
		} elseif (isset($_GET['psl']) && ((int) $_GET['psl']) == 4) {
		  $view->get_BA_View();
		} else {
			$view->get_OZ_View();
		}
	}
 
?>