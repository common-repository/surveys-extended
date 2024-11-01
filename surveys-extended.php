<?php
/*
Plugin Name: Surveys Extended
Plugin URI: http://www.a-sd.de/
Description: The Surveys WordPress plugin lets you add surveys to your blog. You can let the vistors take surveys and see the result from the admin side.
Version: 0.0.6
Author: Andre Pietsch
Author URI: http://www.a-sd.de/
*/

/*  Copyright 2011  Andre Pietsch, Advicio ServDesk GmbH (email: andre.pietsch@a-sd.de)
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301
*/

/**
 * Add a new menu under Manage, visible for all users with template viewing level.
 */
add_action( 'admin_menu', 'surveys_extended_add_menu_links' );
function surveys_extended_add_menu_links() {
	global $wp_version, $_registered_pages;
	$view_level= 2;
	$page = 'edit.php';
	if($wp_version >= '2.7') $page = 'tools.php';
	
	add_submenu_page($page, __('Manage Surveys', 'surveyse'), __('Manage Surveys', 'surveyse'), $view_level, 'surveys-extended/survey.php');
	
	$code_pages = array('export.php','export_choose.php','individual_responses.php','question.php','question_form.php','responses.php','show_individual_response.php','survey_action.php','survey_form.php');
	foreach($code_pages as $code_page) {
		$hookname = get_plugin_page_hookname("surveys-extended/$code_page", '' );
		$_registered_pages[$hookname] = true;
	}
}

/**
 * This will scan all the content pages that wordpress outputs for our special code. If the code is found, it will replace the requested survey.
 */
add_shortcode( 'SURVEYS_EXTENDED', 'surveys_extended_shortcode' );
function surveys_extended_shortcode( $attr ) {
	$survey_id = $attr[0];
	
	$contents = '';
	if(is_numeric($survey_id)) { // Basic validiation - more on the show_quiz.php file.
		ob_start();
		include(ABSPATH . 'wp-content/plugins/surveys-extended/show_survey.php');
		$contents = ob_get_contents();
		ob_end_clean();
	}
	return $contents;
}

/// Add an option page for surveys.
add_action('admin_menu', 'surveys_extended_option_page');
function surveys_extended_option_page() {
	add_options_page(__('Surveys Settings', 'surveyse'), __('Surveys Settings', 'surveyse'), 8, basename(__FILE__), 'surveys_extended_options');
}
function surveys_extended_options() {
	if ( function_exists('current_user_can') && !current_user_can('manage_options') ) die(__("Cheatin' uh?", 'surveyse'));
	if (! user_can_access_admin_page()) wp_die( __('You do not have sufficient permissions to access this page.', 'surveyse') );

	require(ABSPATH. '/wp-content/plugins/surveys-extended/options.php');
}


register_activation_hook( __FILE__ , 'surveys_extended_activate');
function surveys_extended_activate() {
	global $wpdb;
	
	// Initial options.
	add_option('surveys_extended_questions_per_page', 1);
	add_option('surveys_extended_insert_csv_header', 1);
	
	$database_version = '5';
	$installed_db = get_option('surveys_extended_db_version');
	
	if($database_version != $installed_db) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		// Create the table structure
		$sql = "CREATE TABLE {$wpdb->prefix}surveys_extended_answer (
					ID int(11) unsigned NOT NULL auto_increment,
					question_ID int(11) unsigned NOT NULL,
					answer varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					sort_order int(3) NOT NULL,
					INDEX ( question_ID ),
					PRIMARY KEY  (ID)
					) ;
				CREATE TABLE {$wpdb->prefix}surveys_extended_question (
					ID int(11) unsigned NOT NULL auto_increment,
					survey_ID int(11) unsigned NOT NULL,
					question mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					allow_user_answer int(1) NOT NULL default '0',
					allow_multiple_answers int(2) NOT NULL default '0',
					user_answer_format enum('entry','textarea','checkbox') NOT NULL default 'entry',
					sort_order int(11) unsigned NOT NULL default '0',
					PRIMARY KEY  (ID),
					KEY survey_id (survey_ID)
					) ;
				CREATE TABLE {$wpdb->prefix}surveys_extended_result (
					ID int(11) unsigned NOT NULL auto_increment,
					survey_ID int(11) unsigned NOT NULL,
					name varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					email varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					added_on datetime NOT NULL,
					INDEX ( survey_ID ),
					PRIMARY KEY  (ID)
					) ;
				CREATE TABLE {$wpdb->prefix}surveys_extended_result_answer (
					ID int(11) unsigned NOT NULL auto_increment,
					result_ID int(11) unsigned NOT NULL,
					answer_ID int(11) unsigned NOT NULL,
					question_ID INT( 11 ) UNSIGNED NOT NULL,
					user_answer VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					INDEX ( question_ID ),
					INDEX ( answer_ID ),
					INDEX ( result_ID ),
					PRIMARY KEY  (ID)
					) ;
				CREATE TABLE {$wpdb->prefix}surveys_extended_survey (
					ID int(11) unsigned NOT NULL auto_increment,
					name varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					description mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					final_screen mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					status enum('1','0') NOT NULL default '0',
					added_on datetime NOT NULL,
					PRIMARY KEY  (ID)
					) ;";
		
		if($database_version == 2) {
			$wpdb->query("UPDATE {$wpdb->prefix}surveys_extended_result_answer RA 
				SET question_ID=(SELECT question_ID FROM {$wpdb->prefix}surveys_extended_answer WHERE ID=RA.answer_ID)");
		}
		dbDelta($sql);
		update_option( "surveys_extended_db_version", $database_version );
	}
}
