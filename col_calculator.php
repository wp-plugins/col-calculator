<?php

/*
	Plugin Name: Cost of Living Calculator
	Description: Creates the Cost of Living Calculator on WordPress
	Version: 0.2
	Author: pgogy
	Author URI: http://www.pgogywebstuff.com
*/

require_once('class-COL_Manage.php');
require_once('class-COL_display_calculator.php');
require_once('class-COL_ManageDataSubmission.php');
require_once('class-COL_Shortcode.php');
require_once('class-COL_Download.php');

class col_calculator_database{

	function col_calculator_deactivate(){
	
	}

	function col_calculator_activate(){

		global $wpdb;
		
		if(!get_option("col_calculator_data")){

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			$table_name = $wpdb->prefix . "col_calculator_responses_data";

			$sql = "CREATE TABLE " . $table_name . " (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  name  varchar(20),
				  zip  int(10),
				  place varchar(50),
				  email varchar(100),
				  compensation bigint(20),
				  classes bigint(20),
				  response varchar(1000),
				  date_submitted bigint(20),
				  UNIQUE KEY id(id)
				);";
			
			dbDelta($sql);
			
			$table_name = $wpdb->prefix . "col_calculator_places_data";

			$sql = "CREATE TABLE " . $table_name . " (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  place  varchar(50),
				  rent  bigint(20),
				  UNIQUE KEY id(id)
				);";

			dbDelta($sql);
			
			$wpdb->insert( $table_name, array( 'place' => "Atlanta, GA", 'rent' =>48028));
			$wpdb->insert( $table_name, array( 'place' => "Austin, TX", 'rent' =>53680));
			$wpdb->insert( $table_name, array( 'place' => "Baltimore, MD", 'rent' =>50152));
			$wpdb->insert( $table_name, array( 'place' => "Birmingham, AL", 'rent' =>30328));
			$wpdb->insert( $table_name, array( 'place' => "Boston, MA", 'rent' =>97760));
			$wpdb->insert( $table_name, array( 'place' => "Bridgeport. CT", 'rent' =>49800));
			$wpdb->insert( $table_name, array( 'place' => "Buffalo, NY", 'rent' =>29344));
			$wpdb->insert( $table_name, array( 'place' => "Charlotte, NC", 'rent' =>41716));
			$wpdb->insert( $table_name, array( 'place' => "Chicago, IL", 'rent' =>64824));
			$wpdb->insert( $table_name, array( 'place' => "Cincinnati, OH", 'rent' =>31560));
			$wpdb->insert( $table_name, array( 'place' => "Cleveland, OH", 'rent' =>29200));
			$wpdb->insert( $table_name, array( 'place' => "Columbus, OH", 'rent' =>33716));
			$wpdb->insert( $table_name, array( 'place' => "Dallas, TX", 'rent' =>52988));
			$wpdb->insert( $table_name, array( 'place' => "Washington, DC", 'rent' =>89660));
			$wpdb->insert( $table_name, array( 'place' => "Denver, CO", 'rent' =>55200));
			$wpdb->insert( $table_name, array( 'place' => "Des Moines, IA", 'rent' =>29092));
			$wpdb->insert( $table_name, array( 'place' => "Detroit, MI", 'rent' =>29596));
			$wpdb->insert( $table_name, array( 'place' => "Hartford, CT", 'rent' =>35440));
			$wpdb->insert( $table_name, array( 'place' => "Honolulu, HI", 'rent' =>81224));
			$wpdb->insert( $table_name, array( 'place' => "Houston, TX", 'rent' =>60140));
			$wpdb->insert( $table_name, array( 'place' => "Indianapolis, IN", 'rent' =>33620));
			$wpdb->insert( $table_name, array( 'place' => "Kansas City, MO", 'rent' =>33836));
			$wpdb->insert( $table_name, array( 'place' => "Los Angeles, CA", 'rent' =>76984));
			$wpdb->insert( $table_name, array( 'place' => "Las Vegas, NV", 'rent' =>46940));
			$wpdb->insert( $table_name, array( 'place' => "Louisville, KY", 'rent' =>29476));
			$wpdb->insert( $table_name, array( 'place' => "Memphis, TN", 'rent' =>36196));
			$wpdb->insert( $table_name, array( 'place' => "Miami, FL", 'rent' =>91392));
			$wpdb->insert( $table_name, array( 'place' => "Milwaukee, WI", 'rent' =>33224));
			$wpdb->insert( $table_name, array( 'place' => "Minneapolis, MN", 'rent' =>52840));
			$wpdb->insert( $table_name, array( 'place' => "Nashville, TN", 'rent' =>48796));
			$wpdb->insert( $table_name, array( 'place' => "New Haven, CT", 'rent' =>48964));
			$wpdb->insert( $table_name, array( 'place' => "New Orleans, LA", 'rent' =>50868));
			$wpdb->insert( $table_name, array( 'place' => "New York City, NY", 'rent' =>109200));
			$wpdb->insert( $table_name, array( 'place' => "Oklahoma City, OK", 'rent' =>35920));
			$wpdb->insert( $table_name, array( 'place' => "Orlando, FL", 'rent' =>41896));
			$wpdb->insert( $table_name, array( 'place' => "Philadelphia, PA", 'rent' =>49512));
			$wpdb->insert( $table_name, array( 'place' => "Phoenix, AZ", 'rent' =>40916));
			$wpdb->insert( $table_name, array( 'place' => "Pittsburgh, PA", 'rent' =>43476));
			$wpdb->insert( $table_name, array( 'place' => "Portland, OR", 'rent' =>51260));
			$wpdb->insert( $table_name, array( 'place' => "Providence, RI", 'rent' =>49140));
			$wpdb->insert( $table_name, array( 'place' => "Raleigh, NC", 'rent' =>45680));
			$wpdb->insert( $table_name, array( 'place' => "Richmond, VA", 'rent' =>39776));
			$wpdb->insert( $table_name, array( 'place' => "Rochester, NY", 'rent' =>31200));
			$wpdb->insert( $table_name, array( 'place' => "Sacramento, CA", 'rent' =>45200));
			$wpdb->insert( $table_name, array( 'place' => "Salt Lake City, UT", 'rent' =>41200));
			$wpdb->insert( $table_name, array( 'place' => "San Antonio, TX", 'rent' =>43172));
			$wpdb->insert( $table_name, array( 'place' => "San Diego, CA", 'rent' =>78356));
			$wpdb->insert( $table_name, array( 'place' => "San Francisco, CA", 'rent' => 140780));
			$wpdb->insert( $table_name, array( 'place' => "San Jose, CA", 'rent' =>92924));
			$wpdb->insert( $table_name, array( 'place' => "Seattle, WA", 'rent' =>65624));
			$wpdb->insert( $table_name, array( 'place' => "Springfield, MA", 'rent' =>23008));
			$wpdb->insert( $table_name, array( 'place' => "St Louis, MO", 'rent' =>31764));
			$wpdb->insert( $table_name, array( 'place' => "Stamford, CT", 'rent' =>81000));
			$wpdb->insert( $table_name, array( 'place' => "Tampa, FL", 'rent' =>45644));
			$wpdb->insert( $table_name, array( 'place' => "Trenton, NJ", 'rent' =>43416));

			$places = $wpdb->get_results("SELECT id,place,rent FROM " . $table_name . " order by place ASC");
		
			$js = "var places = new Array();";
			
			foreach($places as $place){
			
				if($place->place!=""){
			
					$js .= 'places["' . $place->place . '"] = ' . $place->rent . ';';
				
				}
				
			}
		
			file_put_contents(plugin_dir_path(__FILE__) . "js/col_calculate_places.js", $js);
			
			add_option("col_calculator_data", TRUE);
			
		}
		
	}

}

$col = new col_calculator_database();

register_activation_hook( __FILE__, array($col,'col_calculator_activate'));
register_deactivation_hook( __FILE__ , array($col,'col_calculator_deactivate'));
